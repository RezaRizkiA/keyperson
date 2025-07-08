@extends('layout') {{-- Sesuaikan dengan nama layout utama Anda --}}

@section('top')
    {{-- Anda bisa menambahkan section 'top' di sini jika diperlukan --}}
@endsection

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card text-center border shadow-sm">
                <div class="card-body p-4 p-md-5">
                    <h4 class="fw-bolder">Selesaikan Pembayaran Anda</h4>
                    <p class="text-muted">Segera lakukan pembayaran sebelum tanggal kedaluwarsa: <br><strong class="text-danger">{{ $transaction->expired_date->format('d F Y, Pukul H:i') }} WIB</strong></p>

                    <hr class="my-4">

                    <div class="mb-4">
                        <p class="mb-1 text-muted">Metode Pembayaran:</p>
                        <h5 class="fw-bold">{{ $transaction->payment_name }}</h5>
                    </div>

                    <div class="mb-4">
                        <p class="mb-1 text-muted">Nomor Pembayaran / Virtual Account:</p>
                        <div class="d-flex justify-content-center align-items-center gap-2 bg-light p-3 rounded">
                            @if($transaction->via === 'QRIS')
                                <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(700)->margin(2)->backgroundColor(255,255,255)->generate($transaction->paymentNo)) !!}" alt="QRIS" class="img-fluid" style="max-width: 150px;">
                            @else
                            <h3 class="fw-bolder text-primary mb-0" id="paymentNumber">{{ $transaction->paymentNo }}</h3>
                            <button class="btn btn-sm btn-outline-secondary" onclick="copyToClipboard()">
                                <i class="bi bi-clipboard"></i> Salin
                            </button>
                            @endif
                        </div>
                    </div>

                    <div class="mb-4">
                        <p class="mb-1 text-muted">Total Tagihan:</p>
                        <h3 class="fw-bolder">Rp {{ number_format($transaction->total, 0, ',', '.') }}</h3>
                    </div>

                    <div class="alert alert-info mt-4">
                        Silakan selesaikan pembayaran melalui ATM, Internet Banking, atau Mobile Banking. Setelah pembayaran berhasil, status akan diperbarui secara otomatis.
                    </div>

                    <a href="{{ route('profile') }}" class="btn btn-secondary mt-3">Kembali ke Profile</a>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Tambahkan script untuk menyalin nomor pembayaran --}}
<script>
function copyToClipboard() {
    // Buat elemen textarea sementara
    const el = document.createElement('textarea');
    el.value = document.getElementById('paymentNumber').innerText;
    document.body.appendChild(el);
    el.select();
    document.execCommand('copy');
    document.body.removeChild(el);

    // Beri notifikasi ke pengguna
    alert('Nomor pembayaran berhasil disalin!');
}
</script>
@endsection
