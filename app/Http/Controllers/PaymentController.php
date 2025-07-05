<?php
namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Ipaymu;
use Illuminate\Http\Request;

class PaymentController extends Controller
{

    public function show(Appointment $appointment)
    {
        // Pastikan appointment ini milik user yang sedang login
        if ($appointment->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // 2. Ambil semua channel pembayaran dari DATABASE LOKAL
        $paymentChannels = Ipaymu::all();

        // 3. Decode kolom 'channels' dari JSON menjadi array agar bisa di-loop di view
        //    Ini penting untuk menampilkan daftar bank VA atau minimarket
        $paymentChannels->each(function ($channel) {
            $channel->channels = json_decode($channel->channels, true);
        });

        // 4. Kirim data ke view
        return view('payment.show', compact('appointment', 'paymentChannels'));
    }

    public function process(Request $request)
    {
        dd($request->all());
    }

    /**
     * Process the payment request.
     */
    // public function process(Request $request, Appointment $appointment)
    // {
    //     $request->validate([
    //         'payment_method' => 'required|string',
    //     ]);

    //     // Pastikan appointment ini milik user yang sedang login
    //     if ($appointment->user_id !== auth()->id()) {
    //         abort(403, 'Unauthorized action.');
    //     }

    //     DB::beginTransaction();
    //     try {
    //         // Buat transaksi di iPaymu dan dapatkan data yang sudah dipetakan
    //         $transactionData = $this->ipaymuService->createTransaction($appointment, $request->payment_method);

    //         if (! $transactionData) {
    //             throw new \Exception('Failed to create transaction with iPaymu.');
    //         }

    //         // Simpan detail transaksi ke database lokal menggunakan data yang sudah dipetakan
    //         $transaction = Transaction::create($transactionData);

    //                                                                           // Update status pembayaran appointment
    //         $appointment->update(['payment_status' => $transaction->status]); // Gunakan status dari transaksi

    //         DB::commit();

    //         // Redirect user ke URL pembayaran iPaymu
    //         return redirect()->away($transaction->url);

    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         Log::error('Payment processing failed: ' . $e->getMessage());
    //         return redirect()->back()->with('error', 'Payment processing failed. Please try again.');
    //     }
    // }

    /**
     * Handle iPaymu callback notifications.
     */
    // public function callback(Request $request)
    // {
    //     Log::info('iPaymu Callback Received:', $request->all());

    //     $sid         = $request->input('sid');
    //     $status      = $request->input('status');      // iPaymu status code (1=success, 2=pending, etc.)
    //     $referenceId = $request->input('referenceId'); // Our refId
    //     $signature   = $request->input('signature');
    //     $trxId       = $request->input('trxId'); // iPaymu's transaction ID
    //     $paymentNo   = $request->input('paymentNo');
    //     $via         = $request->input('via');
    //     $amount      = $request->input('amount');
    //     $fee         = $request->input('fee');
    //     $paymentDate = $request->input('paymentDate');
    //     $expiredDate = $request->input('expiredDate');
    //     $channel     = $request->input('channel');
    //     $type        = $request->input('type');
    //     $phone       = $request->input('phone');
    //     $email       = $request->input('email');
    //     $name        = $request->input('name');
    //     $paymentName = $request->input('paymentName');

    //     // Data yang digunakan untuk verifikasi signature
    //     $dataToVerify = [
    //         'sid'    => $sid,
    //         'status' => $status,
    //     ];

    //     // Verifikasi signature
    //     if (! $this->ipaymuService->verifyCallbackSignature($dataToVerify, $signature)) {
    //         Log::warning('iPaymu Callback: Invalid signature for referenceId ' . $referenceId);
    //         return response()->json(['message' => 'Invalid Signature'], 403);
    //     }

    //     DB::beginTransaction();
    //     try {
    //         $transaction = Transaction::where('refId', $referenceId)->first(); // Use refId as our reference

    //         if (! $transaction) {
    //             Log::warning('iPaymu Callback: Transaction not found for refId ' . $referenceId);
    //             return response()->json(['message' => 'Transaction Not Found'], 404);
    //         }

    //         $appointment = $transaction->appointment;

    //         // Map iPaymu status to our internal status
    //         $internalStatus = 'failed';
    //         if ($status == '1') { // Success
    //             $internalStatus = 'paid';
    //         } elseif ($status == '2') { // Pending
    //             $internalStatus = 'pending';
    //         } elseif ($status == '0') { // Failed
    //             $internalStatus = 'failed';
    //         }

    //         // Update transaction details
    //         $transaction->update([
    //             'trx_id'               => $trxId,
    //             'status'               => $internalStatus,
    //             'sid'                  => $sid,
    //             'paymentName'          => $paymentName,
    //             'paymentNo'            => $paymentNo,
    //             'via'                  => $via,
    //             'payment_date'         => $paymentDate ? \Carbon\Carbon::parse($paymentDate) : null,
    //             'expired_date'         => $expiredDate ? \Carbon\Carbon::parse($expiredDate) : null,
    //             'amount'               => $amount,
    //             'trx_fee'              => $fee,
    //             'channel'              => $channel,
    //             'type'                 => $type,
    //             'phone'                => $phone,
    //             'email'                => $email,
    //             'name'                 => $name,
    //             'trx_chacking'         => ($internalStatus === 'paid'), // Set true if paid
    //             'trx_calender_process' => ($internalStatus === 'paid'), // Set true if paid, to trigger calendar job
    //         ]);

    //         // Update appointment payment status
    //         $appointment->update(['payment_status' => $internalStatus]);

    //         DB::commit();
    //         Log::info('iPaymu Callback: Transaction ' . $referenceId . ' updated to ' . $internalStatus);
    //         return response()->json(['message' => 'Callback Processed Successfully'], 200);

    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         Log::error('iPaymu Callback processing failed for refId ' . $referenceId . ': ' . $e->getMessage());
    //         return response()->json(['message' => 'Internal Server Error'], 500);
    //     }
    // }

    /**
     * Display a success page after payment.
     */
    // public function success(Appointment $appointment)
    // {
    //     // Pastikan appointment ini milik user yang sedang login
    //     if ($appointment->user_id !== auth()->id()) {
    //         abort(403, 'Unauthorized action.');
    //     }

    //     // Anda bisa menambahkan logika untuk menampilkan detail transaksi atau pesan sukses
    //     return view('payment.success', compact('appointment'));
    // }
}
