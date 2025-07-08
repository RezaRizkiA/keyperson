<?php
namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Ipaymu;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PaymentController extends Controller
{

    public function show(Appointment $appointment)
    {
        $userId = auth()->id();
        if ($appointment->user_id !== $userId) {
            abort(403, 'Unauthorized action.');
        }

        $paymentChannels = Ipaymu::all();

        $paymentChannels->each(function ($channel) {
            $channel->channels = json_decode($channel->channels, true);
        });
        return view('payment.show', compact('appointment', 'paymentChannels'));
    }

    public function process(Appointment $appointment, Request $request)
    {
        $userId = auth()->id();
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

        if ($appointment->transactions()->where('status', 'paid')->exists()) {
            return back()->with('error', 'This appointment has already been paid.');
        }

        $user     = auth()->user();
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
            // 'product'        => $appointment->appointment,
            // 'qty'            => $appointment->hours,
            // 'price'          => $appointment->price,
        ];

        // memanggil fungsi helper
        $ipaymuResponse = directPaymentIpaymu($body);

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
        // Cari transaksi berdasarkan sid
        $transaction = Transaction::where('sid', $sid_transaction)->firstOrFail();

        // Cek apakah transaksi sudah dibayar
        if ($transaction->status === 'paid') {
            return redirect()->route('profile')->with('success', 'Payment has been completed successfully.');
        }

        // Cek apakah transaksi sudah kadaluarsa
        if ($transaction->expired_date < now()) {
            return redirect()->route('profile')->with('error', 'Payment has expired. Please create a new transaction.');
        }
        // Tampilkan halaman transaksi
        return view('payment.transaction', compact('transaction'));
    }
}
