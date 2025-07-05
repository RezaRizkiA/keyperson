@extends('layout')

@section('top')
    <section class="py-4 py-md-5 bg-light-gray">
        <div class="container-fluid">
            <div class="d-flex justify-content-between flex-md-nowrap flex-wrap">
                <h2 class="fs-13 fw-bolder">
                    Payment Success
                </h2>
                <div class="d-flex align-items-center gap-6">
                    <a href="{{ route('home') }}" class="text-muted fw-bolder link-primary fs-3 text-uppercase">
                        Home
                    </a>
                    <iconify-icon icon="solar:alt-arrow-right-outline" class="fs-5 text-muted"></iconify-icon>
                    <a href="#" class="text-primary link-primary fw-bolder fs-3 text-uppercase">
                        Payment Success
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
                <div class="card-body p-4 text-center">
                    <h4 class="fw-semibold mb-3 text-success">Payment Successful!</h4>
                    <p class="mb-4">Your appointment payment has been successfully processed.</p>

                    <div class="table-responsive mb-4">
                        <table class="table mx-auto" style="max-width: 500px;">
                            <tr>
                                <td class="fw-semibold text-start">Expert</td>
                                <td class="text-start">{{ $appointment->expert->user->name }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold text-start">Date & Time</td>
                                <td class="text-start">{{ $appointment->date_time->format('d F Y, H:i') }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold text-start">Total Price</td>
                                <td class="text-start fw-bold">Rp. {{ number_format($appointment->price * $appointment->hours, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold text-start">Payment Status</td>
                                <td class="text-start text-success fw-bold">{{ ucfirst($appointment->payment_status) }}</td>
                            </tr>
                        </table>
                    </div>

                    <p>You will receive a confirmation email shortly.</p>
                    <a href="{{ route('profile') }}" class="btn btn-primary mt-3">Go to My Appointments</a>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('footer')
    @include('partials.footer')
@endsection
