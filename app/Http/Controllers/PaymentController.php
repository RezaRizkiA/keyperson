<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePaymentRequest;
use App\Jobs\CreateGoogleCalendarEvent;
use App\Mail\AppointmentInvitation;
use App\Models\Appointment;
use App\Models\Ipaymu;
use App\Models\Transaction;
use App\Models\User;
use App\Services\PaymentService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PaymentController extends Controller
{
    protected $service;

    public function __construct(PaymentService $service)
    {
        $this->service = $service;
    }

    // 1. CREATE (Halaman Pilih Pembayaran)
    // URL: GET /payment/{appointment}/checkout
    public function create(Appointment $appointment)
    {
        // Validasi sederhana status (bisa dipindah ke policy nanti)
        if ($appointment->payment_status === 'paid') {
            return redirect()->back()->with('info', 'Appointment already paid.');
        }

        // Ambil Channel Pembayaran untuk Dropdown Frontend
        $channels = Ipaymu::all()->map(function ($c) {
            $c->channels = json_decode($c->channels, true);

            return $c;
        });

        return Inertia::render('User/Payment/Create', [
            'appointment' => $appointment->load('expert.user'),
            'paymentChannels' => $channels,
        ]);
    }

    // 2. STORE (Proses ke iPaymu)
    // URL: POST /payment/{appointment}/checkout
    public function store(StorePaymentRequest $request, Appointment $appointment)
    {
        try {
            // Panggil Service untuk urus iPaymu & DB
            $transaction = $this->service->processPayment($appointment, $request->validated());

            return redirect()->route('payment.transaction', ['sid' => $transaction->sid]);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

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
            'paymentMethod' => 'required|string',
            'paymentChannel' => 'required|string',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|min:10|max:15',
            'customer_email' => 'required|email|max:255',
        ]);
        // dd(request()->all());

        if ($appointment->transactions()->where('status', 'berhasil')->exists()) {
            return back()->with('error', 'This appointment has already been paid.');
        }

        $user = Auth::user();
        $amount = $appointment->price * $appointment->hours;
        $trx_desc = $user->name.' - '.$appointment->expert->user->name;

        $body = [
            'name' => $user->name,
            'phone' => $user->phone ?? $request->customer_phone,
            'email' => $user->email,
            'amount' => $amount,
            'notifyUrl' => route('payment.notify'),
            'referenceId' => $appointment->id,
            'paymentMethod' => $request->paymentMethod,
            'paymentChannel' => $request->paymentChannel,
            'expired_date' => trim(24),
            'description' => $trx_desc,
            'feeDirection' => 'BUYER',
        ];

        // memanggil fungsi helper
        $ipaymuResponse = directPaymentIpaymu($body);

        if (! $ipaymuResponse || ! isset($ipaymuResponse['SessionId'])) {
            // Debugging: Log error untuk melihat pesan asli dari iPaymu (jika ada)
            Log::error('Ipaymu Payment Failed', ['body' => $body, 'response' => $ipaymuResponse]);

            // Kembalikan user ke halaman sebelumnya dengan pesan error
            return back()->with('error', 'Gagal memproses pembayaran. Silakan coba lagi atau pilih metode lain.');
        }

        $transaction = Transaction::create([
            'appointment_id' => $appointment->id,
            'user_id' => $user->id,
            'name' => $body['name'],
            'email' => $body['email'],
            'phone' => $body['phone'],
            'trx_desc' => $trx_desc,
            'feeDirection' => $body['feeDirection'],
            'amount' => $amount,
            'url' => route('payment.notify'),
            'sid' => randomCode(),

            // Simpan data dari respons iPaymu
            'sessionID' => $ipaymuResponse['SessionId'],
            'transactionId' => $ipaymuResponse['TransactionId'],
            'referenceId' => $ipaymuResponse['ReferenceId'],
            'via' => $ipaymuResponse['Via'],
            'channel' => $ipaymuResponse['Channel'],
            'paymentNo' => $ipaymuResponse['PaymentNo'],
            'paymentName' => $ipaymuResponse['PaymentName'],
            'total' => $ipaymuResponse['Total'],
            'trx_fee' => $ipaymuResponse['Fee'],
            'expired_date' => Carbon::parse($ipaymuResponse['Expired']),
        ]);

        // --- Langkah 7: Mengarahkan Pengguna ---
        return redirect()->route('payment.transaction', ['sid' => $transaction->sid]);
    }

    // PaymentController.php

    public function transaction($sid)
    {
        // Cari Transaksi berdasarkan SID
        $transaction = Transaction::where('sid', $sid)
            ->with('appointment.expert.user') // Load relasi untuk detail tampilan
            ->firstOrFail();

        // 1. Jika Sukses -> Arahkan ke Detail Appointment (bukan Profile umum)
        if ($transaction->status === 'berhasil') {
            return redirect()->route('dashboard.appointments.show', $transaction->appointment_id)
                ->with('success', 'Payment successful! Your session is confirmed.');
        }

        // 2. Jika Expired -> Beri opsi buat ulang
        if ($transaction->expired_date < now()) {
            // Logikanya: User harus booking ulang atau checkout ulang
            return redirect()->route('booking.create', $transaction->appointment->expert_id)
                ->with('error', 'Payment expired. Please book again.');
        }

        // 3. QRIS Generation (Logic View)
        $qrCodeImage = null;
        if ($transaction->via === 'QRIS' && $transaction->paymentNo) {
            $qrCodeImage = 'data:image/png;base64,'.base64_encode(
                QrCode::format('png')
                    ->size(300)
                    ->margin(1)
                    ->generate($transaction->paymentNo)
            );
        }

        return Inertia::render('User/Payment/Transaction', [
            'transaction' => $transaction,
            'qrCodeImage' => $qrCodeImage,
            // Kirim data appointment untuk konteks (Nama Expert, Tanggal, dll)
            'appointment' => $transaction->appointment,
        ]);
    }

    public function notify(Request $request)
    {
        // dd($request->all());
        // 1. Log Incoming Data (Untuk Debugging)
        Log::info('iPaymu Webhook:', $request->all());

        $trxId = $request->input('trx_id');
        if (! $trxId) {
            return response()->json(['error' => 'No trx_id'], 400);
        }

        // 2. Cari Transaksi
        $transaction = Transaction::where('transactionId', $trxId)->first();
        if (! $transaction) {
            return response()->json(['error' => 'Transaction not found'], 404);
        }

        // 3. Jika sudah 'berhasil', stop (Idempotency)
        if ($transaction->status === 'berhasil') {
            return response()->json(['message' => 'Already paid']);
        }

        // 4. Double Check Status ke Server iPaymu (Security Best Practice)
        // Asumsi helper checkTransactionIpaymu() mengembalikan array response iPaymu
        $check = checkTransactionIpaymu($trxId);

        if (isset($check['StatusDesc']) && $check['StatusDesc'] === 'Berhasil') {

            try {
                DB::transaction(function () use ($transaction) {
                    // A. Update Status Transaksi
                    $transaction->update([
                        'status' => 'berhasil',
                        'payment_date' => Carbon::now(),
                        // Update field lain jika perlu dari $check response
                    ]);

                    // === B2B: CHECK TRANSACTION TYPE ===
                    if ($transaction->type === 'topup') {
                        // TOPUP: Tambahkan quota ke perusahaan
                        $this->handleTopUpPayment($transaction);
                    } else {
                        // BOOKING: Logic pembayaran appointment (flow lama)
                        $this->handleBookingPayment($transaction);
                    }
                    // === END B2B ===
                });

                return response()->json(['status' => 'success']);
            } catch (\Exception $e) {
                Log::error('Webhook Error: '.$e->getMessage());

                return response()->json(['error' => 'Internal Server Error'], 500);
            }
        }

        return response()->json(['message' => 'Payment status is: '.($check['StatusDesc'] ?? 'Unknown')]);
    }

    /**
     * Handle booking payment (flow lama)
     */
    private function handleBookingPayment(Transaction $transaction): void
    {
        $appointment = $transaction->appointment;

        if (! $appointment) {
            Log::warning('Booking payment without appointment', ['transaction_id' => $transaction->id]);

            return;
        }

        $appointment->update([
            'payment_status' => 'paid',
            'status' => 'confirmed', // Appointment sah
        ]);

        // 1. Job Google Calendar
        CreateGoogleCalendarEvent::dispatch($appointment);

        // 2. Kirim Email Undangan
        Mail::to($appointment->user->email)
            ->send(new AppointmentInvitation($appointment));

        // Kirim ke Tamu (Looping array guests)
        if ($appointment->type === 'group' && ! empty($appointment->guests)) {
            foreach ($appointment->guests as $guestEmail) {
                Mail::to($guestEmail)
                    ->send(new AppointmentInvitation($appointment));
            }
        }
    }

    /**
     * B2B: Handle top-up payment
     */
    private function handleTopUpPayment(Transaction $transaction): void
    {
        $user = $transaction->user;

        if (! $user || ! $user->isCorporateMember() || ! $user->company) {
            Log::error('TopUp payment: User is not a corporate member', ['transaction_id' => $transaction->id]);

            return;
        }

        // Tambahkan quota menggunakan QuotaService
        $quotaService = app(\App\Services\QuotaService::class);
        $quotaService->addQuota(
            $user->company,
            $transaction->amount,
            $transaction,
            $user
        );

        Log::info('TopUp payment successful', [
            'transaction_id' => $transaction->id,
            'client_id' => $user->company->id,
            'amount' => $transaction->amount,
        ]);
    }
}
