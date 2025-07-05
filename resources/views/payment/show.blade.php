@extends('layout')

@section('top')
    <section class="py-4 py-md-5 bg-light-gray">
        <div class="container-fluid">
            <div class="d-flex justify-content-between flex-md-nowrap flex-wrap">
                <h2 class="fs-13 fw-bolder">
                    Payment
                </h2>
                <div class="d-flex align-items-center gap-6">
                    <a href="{{ route('home') }}" class="text-muted fw-bolder link-primary fs-3 text-uppercase">
                        Home
                    </a>
                    <iconify-icon icon="solar:alt-arrow-right-outline" class="fs-5 text-muted"></iconify-icon>
                    <a href="#" class="text-primary link-primary fw-bolder fs-3 text-uppercase">
                        Payment
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('content')
    <section class="pt-5 pt-md-14 pb-4 pb-md-5">
        <div class="container-fluid">
            <div class="card border">
                <div class="card-body p-4">
                    <h4 class="fw-semibold mb-3">Appointment Details</h4>
                    <div class="table-responsive">
                        <table class="table">
                            <tr>
                                <td class="fw-semibold">Expert</td>
                                <td>{{ $appointment->expert->user->name }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Date & Time</td>
                                <td>{{ $appointment->date_time->format('d F Y, H:i') }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Duration</td>
                                <td>{{ $appointment->hours }} hour(s)</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Total Price</td>
                                <td class="fw-bold">Rp.
                                    {{ number_format($appointment->price * $appointment->hours, 0, ',', '.') }}</td>
                            </tr>
                        </table>
                    </div>

                    <hr class="my-4">

                    <h4 class="fw-semibold mb-3">Select Payment Method</h4>
                    {{-- Logika yang sudah diperbaiki untuk mengambil data dari database --}}
                    @if ($paymentChannels->isNotEmpty())
                        <form action="{{ route('payment.process', ['appointment' => $appointment->id]) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="payment_method" class="form-label">Payment Method</label>
                                <select name="payment_method" id="payment_method" class="form-select" required>
                                    <option value="" disabled selected>-- Select a payment method --</option>

                                    {{-- Loop untuk setiap grup pembayaran (VA, CStore, QRIS) --}}
                                    @foreach ($paymentChannels as $group)

                                        {{-- Jika grupnya adalah QRIS, tampilkan sebagai satu pilihan --}}
                                        @if ($group->code === 'qris')
                                            <option value="qris">QRIS</option>
                                        @else
                                            {{-- Jika bukan QRIS (VA atau CStore), loop sub-channelnya --}}
                                            @foreach ($group->channels as $channel)
                                                <option value="{{ $channel['Code'] }}">{{ $channel['Name'] }}</option>
                                            @endforeach
                                        @endif

                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Proceed to Payment</button>
                        </form>
                    @else
                        <div class="alert alert-danger" role="alert">
                            Could not retrieve payment methods at this time. Please go back and try again later.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection

@section('footer')
    @include('partials.footer')
@endsection
