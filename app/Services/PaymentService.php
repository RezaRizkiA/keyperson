<?php

namespace App\Services;

use App\Repositories\TransactionRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PaymentService
{
  protected $repo;

  public function __construct(TransactionRepository $repo)
  {
    $this->repo = $repo;
  }

  public function processPayment($appointment, $validatedData)
  {
    $user = Auth::user();

    // 1. Siapkan Data untuk Helper iPaymu
    // Logic ini yang sebelumnya mengotori Controller
    $amount   = $appointment->price;
    $trxDesc  = "Consultation: {$user->name} with {$appointment->expert->user->name}";

    $ipaymuBody = [
      'name'           => $user->name,
      'email'          => $user->email,
      'phone'          => $validatedData['customer_phone'],
      'amount'         => $amount,
      'notifyUrl'      => route('payment.notify'), // Webhook URL
      'referenceId'    => (string) $appointment->id,
      'paymentMethod'  => $validatedData['payment_method'],  // Semantic naming
      'paymentChannel' => $validatedData['payment_channel'], // Semantic naming
      'description'    => $trxDesc,
      'expired'        => 24, // 24 Jam
      'feeDirection'   => 'BUYER',
    ];

    // 2. Panggil Helper iPaymu (Yang sudah Anda miliki)
    // Kita bungkus try-catch atau pengecekan disini
    $response = directPaymentIpaymu($ipaymuBody);

    // Validasi Response Helper
    if (!$response || !isset($response['SessionId'])) {
      Log::error('iPaymu Error', ['body' => $ipaymuBody, 'response' => $response]);
      throw new \Exception('Gagal menghubungi gateway pembayaran.');
    }

    // 3. Susun Data untuk Disimpan ke Database (via Repository)
    $transactionData = [
      'appointment_id' => $appointment->id,
      'user_id'        => $user->id,
      'name'           => $user->name,
      'email'          => $user->email,
      'phone'          => $validatedData['customer_phone'],
      'trx_desc'       => $trxDesc,
      'amount'         => $amount,
      'feeDirection'   => 'BUYER',
      'sid'            => randomCode(), // Asumsi helper randomCode ada

      // Data dari Response iPaymu
      'sessionID'      => $response['SessionId'],
      'transactionId'  => $response['TransactionId'],
      'referenceId'    => $response['ReferenceId'],
      'via'            => $response['Via'],
      'channel'        => $response['Channel'],
      'paymentNo'      => $response['PaymentNo'],
      'paymentName'    => $response['PaymentName'],
      'total'          => $response['Total'],
      'trx_fee'        => $response['Fee'],
      'url'            => $response['Url'] ?? null,
      'expired_date'   => Carbon::now()->addHours(24),
      'status'         => 'pending',
    ];

    // 4. Simpan via Repo
    return $this->repo->create($transactionData);
  }

  /**
   * B2B: Process top-up payment
   * 
   * @param \App\Models\Transaction $transaction Pre-created transaction record
   * @param array $paymentData Payment method data
   * @return \App\Models\Transaction Updated transaction with iPaymu data
   */
  public function processTopUpPayment($transaction, $paymentData)
  {
    $user = Auth::user();

    // 1. Siapkan Data untuk Helper iPaymu
    $ipaymuBody = [
      'name'           => $user->name,
      'email'          => $user->email,
      'phone'          => $user->phone ?? '',
      'amount'         => $transaction->amount,
      'notifyUrl'      => route('payment.notify'), // Webhook URL sama
      'referenceId'    => (string) $transaction->id,
      'paymentMethod'  => $paymentData['payment_method'],
      'paymentChannel' => $paymentData['payment_channel'] ?? $paymentData['payment_method'],
      'description'    => $transaction->trx_desc,
      'expired'        => 24, // 24 Jam
      'feeDirection'   => 'BUYER',
    ];

    // 2. Panggil Helper iPaymu
    $response = directPaymentIpaymu($ipaymuBody);

    // Validasi Response Helper
    if (!$response || !isset($response['SessionId'])) {
      Log::error('iPaymu TopUp Error', ['body' => $ipaymuBody, 'response' => $response]);
      throw new \Exception('Gagal menghubungi gateway pembayaran.');
    }

    // 3. Update transaction dengan data iPaymu
    $transaction->update([
      'sessionID'      => $response['SessionId'],
      'transactionId'  => $response['TransactionId'],
      'referenceId'    => $response['ReferenceId'],
      'via'            => $response['Via'],
      'channel'        => $response['Channel'],
      'paymentNo'      => $response['PaymentNo'],
      'paymentName'    => $response['PaymentName'],
      'total'          => $response['Total'],
      'trx_fee'        => $response['Fee'],
      'url'            => $response['Url'] ?? null,
      'expired_date'   => Carbon::now()->addHours(24),
      'sid'            => randomCode(),
    ]);

    return $transaction->fresh();
  }
}
