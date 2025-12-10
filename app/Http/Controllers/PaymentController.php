<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Ipaymu;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Jobs\CreateGoogleCalendarEvent;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PaymentController extends Controller
{

    public function show(Appointment $appointment)
    {
        $userId = Auth::id();
        if ($appointment->user_id !== $userId) {
            abort(403, 'Unauthorized action.');
        }

        $appointment->load('expert.user');

        $paymentChannels = Ipaymu::all()->map(function ($channel) {
            // Decode JSON channels agar bisa dibaca JS
            $channel->channels = json_decode($channel->channels, true);
            return $channel;
        });

        return Inertia::render('Client/Payment/Show', [
            'appointment' => $appointment,
            'paymentChannels' => $paymentChannels,
            'user' => Auth::user(), // Data user untuk pre-fill form
        ]);
    }

    public function process(Appointment $appointment, Request $request)
    {
        $userId = Auth::id();
        if ($appointment->user_id !== $userId) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'paymentMethod'  => 'required|string',
            'paymentChannel' => 'required|string',
            'customer_name'  => 'required|string|max:255',
            'customer_phone' => 'required|string|min:10|max:15',
            'customer_email' => 'required|email|max:255',
        ]);
        // dd(request()->all());

        if ($appointment->transactions()->where('status', 'berhasil')->exists()) {
            return back()->with('error', 'This appointment has already been paid.');
        }

        $user     = Auth::user();
        $amount   = $appointment->price * $appointment->hours;
        $trx_desc = $user->name . ' - ' . $appointment->expert->user->name;

        $body = [
            'name'           => $user->name,
            'phone'          => $user->phone ?? $request->customer_phone,
            'email'          => $user->email,
            'amount'         => $amount,
            'notifyUrl'      => route('payment.notify'),
            'referenceId'    => $appointment->id,
            'paymentMethod'  => $request->paymentMethod,
            'paymentChannel' => $request->paymentChannel,
            'expired_date'   => trim(24),
            'description'    => $trx_desc,
            'feeDirection'   => 'BUYER',
        ];

        // memanggil fungsi helper
        $ipaymuResponse = directPaymentIpaymu($body);

        if (!$ipaymuResponse || !isset($ipaymuResponse['SessionId'])) {
            // Debugging: Log error untuk melihat pesan asli dari iPaymu (jika ada)
            \Illuminate\Support\Facades\Log::error('Ipaymu Payment Failed', ['body' => $body, 'response' => $ipaymuResponse]);

            // Kembalikan user ke halaman sebelumnya dengan pesan error
            return back()->with('error', 'Gagal memproses pembayaran. Silakan coba lagi atau pilih metode lain.');
        }

        $transaction = Transaction::create([
            'appointment_id' => $appointment->id,
            'user_id'        => $user->id,
            'name'           => $body['name'],
            'email'          => $body['email'],
            'phone'          => $body['phone'],
            'trx_desc'       => $trx_desc,
            'feeDirection'   => $body['feeDirection'],
            'amount'         => $amount,
            'url'            => route('payment.notify'),
            'sid'            => randomCode(),

            // Simpan data dari respons iPaymu
            'sessionID'      => $ipaymuResponse['SessionId'],
            'transactionId'  => $ipaymuResponse['TransactionId'],
            'referenceId'    => $ipaymuResponse['ReferenceId'],
            'via'            => $ipaymuResponse['Via'],
            'channel'        => $ipaymuResponse['Channel'],
            'paymentNo'      => $ipaymuResponse['PaymentNo'],
            'paymentName'    => $ipaymuResponse['PaymentName'],
            'total'          => $ipaymuResponse['Total'],
            'trx_fee'        => $ipaymuResponse['Fee'],
            'expired_date'   => Carbon::parse($ipaymuResponse['Expired']),
        ]);

        // --- Langkah 7: Mengarahkan Pengguna ---
        return redirect()->route('payment.transaction', ['sid' => $transaction->sid]);
    }

    public function transaction($sid_transaction)
    {
        $transaction = Transaction::where('sid', $sid_transaction)->firstOrFail();

        // 1. Cek Status Paid
        if ($transaction->status === 'berhasil') {
            return redirect()->route('profile')->with('success', 'Payment has been completed successfully.');
        }

        // 2. Cek Expired
        if ($transaction->expired_date < now()) {
            return redirect()->route('profile')->with('error', 'Payment has expired. Please create a new transaction.');
        }

        // 3. Generate QR Code (Jika metode QRIS)
        $qrCodeImage = null;
        if ($transaction->via === 'QRIS') {
            $qrCodeImage = 'data:image/png;base64,' . base64_encode(
                QrCode::format('png')
                    ->size(400) // Ukuran diperbesar untuk kualitas
                    ->margin(1)
                    ->backgroundColor(255, 255, 255)
                    ->generate($transaction->paymentNo)
            );
        }

        return Inertia::render('Client/Payment/Transaction', [
            'transaction' => $transaction,
            'qrCodeImage' => $qrCodeImage,
        ]);
    }

    public function notify(Request $request)
    {
        // terima dan catat semua data yang masuk
        $dataNotify = $request->all();
        Log::info('Payment Notification Received', $dataNotify);

        if (!isset($dataNotify['trx_id'])) {
            Log::error('Missing trx_id in payment notification');
            return response()->json(['error' => 'Missing trx_id'], 400);
        }

        // cari transaksi berdasarkan kolom referenceId
        $transaction = Transaction::where('transactionId', $dataNotify['trx_id'])->first();

        if (!$transaction) {
            Log::error('Transaction not found for trx_id: ' . $dataNotify['trx_id']);
            return response()->json(['error' => 'Transaction not found'], 404);
        }

        $checkTransaction = checkTransactionIpaymu($transaction->transactionId);

        if (isset($checkTransaction['StatusDesc']) && $checkTransaction['StatusDesc'] === 'Berhasil') {
            if ($transaction->status !== 'berhasil') {
                $transaction->update([
                    'status'        => strtolower($checkTransaction['StatusDesc'] ?? 'berhasil'),
                    'status'        => strtolower($checkTransaction['StatusDesc'] ?? 'berhasil'),
                    'trx_id'        => $checkTransaction['TransactionId'] ?? $transaction->transactionId,
                    'reference_id'  => $checkTransaction['ReferenceId'] ?? $transaction->reference_id,
                    'payment_date'  => isset($checkTransaction['SuccessDate']) ? Carbon::parse($checkTransaction['SuccessDate']) : null,
                ]);
                $transaction->appointment->update(['payment_status' => 'berhasil']);
                CreateGoogleCalendarEvent::dispatch($transaction->appointment);
            }
            return response()->json(['status' => 'success', 'message' => 'Payment processed, Google Calendar event created successfully']);
        }
        return response()->json(['message' => 'Payment not yet successful']);
    }
}
