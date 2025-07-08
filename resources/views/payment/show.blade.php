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
            <div class="row">
                {{-- Left Column: Appointment Details --}}
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="card border h-100">
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
                        </div>
                    </div>
                </div>

                {{-- Right Column: Payment Methods --}}
                <div class="col-lg-6">
                    <div class="card border">
                        <div class="card-header bg-white border-bottom">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <strong>Whoops! Ada beberapa masalah dengan input Anda.</strong>
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                        <div class="card-body p-4">
                            <h4 class="fw-semibold mb-3 ">Payment Method</h4>

                            <div class="accordion accordion-flush" id="paymentMethodAccordion">
                                @foreach ($paymentChannels as $channel)
                                    <div class="accordion-item shadow-none border-bottom">
                                        <h2 class="accordion-header" id="heading{{ Str::slug($channel->name) }}">
                                            <button
                                                class="accordion-button collapsed fs-4 fw-semibold px-3 py-4 lh-base border-0 rounded-0"
                                                type="button" data-bs-toggle="collapse"
                                                data-bs-target="#collapse{{ Str::slug($channel->name) }}"
                                                aria-expanded="false"
                                                aria-controls="collapse{{ Str::slug($channel->name) }}">
                                                {{ $channel->name }}
                                                <div class="ms-auto d-flex align-items-center">
                                                    @if ($channel->code == 'cstore')
                                                        @if (!empty($channel->channels))
                                                            @foreach ($channel->channels as $subChannel)
                                                                @if (isset($subChannel['Logo']))
                                                                    <img src="{{ $subChannel['Logo'] }}"
                                                                        alt="{{ $subChannel['Name'] }} Logo" class="me-1"
                                                                        style="max-height: 20px;">
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    @elseif ($channel->code == 'qris')
                                                        @if (!empty($channel->channels) && isset($channel->channels[0]['Logo']))
                                                            <img src="{{ $channel->channels[0]['Logo'] }}"
                                                                alt="{{ $channel->channels[0]['Name'] }} Logo"
                                                                class="me-1" style="max-height: 20px;">
                                                        @endif
                                                    @endif
                                                </div>
                                            </button>
                                        </h2>
                                        <div id="collapse{{ Str::slug($channel->name) }}"
                                            class="accordion-collapse collapse"
                                            aria-labelledby="heading{{ Str::slug($channel->name) }}"
                                            data-bs-parent="#paymentMethodAccordion">
                                            <div class="accordion-body px-3 py-4 fw-normal">
                                                @if (in_array($channel->code, ['cstore', 'va']))
                                                    <div class="accordion accordion-flush"
                                                        id="subChannelAccordion{{ Str::slug($channel->name) }}">
                                                        @foreach ($channel->channels as $subChannel)
                                                            <div class="accordion-item shadow-none border-bottom">
                                                                <h2 class="accordion-header"
                                                                    id="subHeading{{ Str::slug($subChannel['Code']) }}">
                                                                    <button
                                                                        class="accordion-button collapsed fs-4 fw-semibold px-3 py-4 lh-base border-0 rounded-0"
                                                                        type="button" data-bs-toggle="collapse"
                                                                        data-bs-target="#subCollapse{{ Str::slug($subChannel['Code']) }}"
                                                                        aria-expanded="false"
                                                                        aria-controls="subCollapse{{ Str::slug($subChannel['Code']) }}">
                                                                        {{ $subChannel['Name'] }}
                                                                        @if (isset($subChannel['Logo']))
                                                                            <div class="ms-auto d-flex align-items-end">
                                                                                <img src="{{ $subChannel['Logo'] }}"
                                                                                    alt="{{ $subChannel['Name'] }} Logo"
                                                                                    class="me-1"
                                                                                    style="max-height: 20px;">
                                                                            </div>
                                                                        @endif
                                                                    </button>
                                                                </h2>
                                                                <div id="subCollapse{{ Str::slug($subChannel['Code']) }}"
                                                                    class="accordion-collapse collapse"
                                                                    aria-labelledby="subHeading{{ Str::slug($subChannel['Code']) }}"
                                                                    data-bs-parent="#subChannelAccordion{{ Str::slug($channel->name) }}">
                                                                    <div class="accordion-body px-3 py-4 fw-normal">
                                                                        <form
                                                                            action="{{ route('payment.process', ['appointment' => $appointment->id]) }}"
                                                                            method="POST" class="payment-form">
                                                                            @csrf
                                                                            <input type="hidden" name="paymentMethod"
                                                                                value="{{ $channel->code }}">
                                                                            <div class="form-check mb-3">
                                                                                <input class="form-check-input"
                                                                                    type="radio" name="paymentChannel"
                                                                                    id="payment_method_{{ Str::slug($subChannel['Code']) }}"
                                                                                    value="{{ $subChannel['Code'] }}"
                                                                                    checked required>
                                                                                <label class="form-check-label"
                                                                                    for="payment_method_{{ Str::slug($subChannel['Code']) }}">
                                                                                    {{ $subChannel['Name'] }}
                                                                                </label>
                                                                            </div>

                                                                            <div class="mb-3">
                                                                                <label
                                                                                    for="customer_name_{{ Str::slug($subChannel['Code']) }}"
                                                                                    class="form-label">Name</label>
                                                                                <input type="text" class="form-control"
                                                                                    id="customer_name_{{ Str::slug($subChannel['Code']) }}"
                                                                                    name="customer_name"
                                                                                    value="{{ auth()->user()->name ?? '' }}"
                                                                                    required>
                                                                            </div>
                                                                            <div class="mb-3">
                                                                                <label
                                                                                    for="customer_phone_{{ Str::slug($subChannel['Code']) }}"
                                                                                    class="form-label">Phone
                                                                                    Number</label>
                                                                                <input type="text" class="form-control"
                                                                                    id="customer_phone_{{ Str::slug($subChannel['Code']) }}"
                                                                                    name="customer_phone"
                                                                                    value="{{ auth()->user()->phone ?? '' }}"
                                                                                    required>
                                                                            </div>
                                                                            <div class="mb-4">
                                                                                <label
                                                                                    for="customer_email_{{ Str::slug($subChannel['Code']) }}"
                                                                                    class="form-label">Email</label>
                                                                                <input type="email" class="form-control"
                                                                                    id="customer_email_{{ Str::slug($subChannel['Code']) }}"
                                                                                    name="customer_email"
                                                                                    value="{{ auth()->user()->email ?? '' }}"
                                                                                    required>
                                                                            </div>
                                                                            <button type="submit"
                                                                                class="btn btn-primary w-100">PAY
                                                                                NOW</button>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <form
                                                        action="{{ route('payment.process', ['appointment' => $appointment->id]) }}"
                                                        method="POST" class="payment-form">
                                                        @csrf
                                                        <input type="hidden" name="paymentMethod"
                                                            value="{{ $channel->code }}">
                                                        @if (!empty($channel->channels))
                                                            <input type="hidden" name="paymentChannel"
                                                                value="{{ $channel->channels[0]['Code'] }}">
                                                        @endif
                                                        <div class="mb-3">
                                                            <label for="customer_name" class="form-label">Name</label>
                                                            <input type="text" class="form-control" id="customer_name"
                                                                name="customer_name"
                                                                value="{{ auth()->user()->name ?? '' }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="customer_phone" class="form-label">Phone
                                                                Number</label>
                                                            <input type="text" class="form-control"
                                                                id="customer_phone" name="customer_phone"
                                                                value="{{ auth()->user()->phone ?? '' }}" required>
                                                        </div>
                                                        <div class="mb-4">
                                                            <label for="customer_email" class="form-label">Email</label>
                                                            <input type="email" class="form-control"
                                                                id="customer_email" name="customer_email"
                                                                value="{{ auth()->user()->email ?? '' }}" required>
                                                        </div>
                                                        <button type="submit" class="btn btn-primary w-100">PAY
                                                            NOW</button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div> {{-- End Accordion --}}

                            <p class="mt-4 text-muted fs-2">
                                <span class="text-danger">*</span> Make a transaction without hesitation, choose the type
                                of payment you want. For the sake of transaction security, you can convert this transaction
                                to an Escrow Transaction by clicking the "Escrow Transaction" button in the payment
                                confirmation e-mail.
                            </p>

                        </div>
                    </div>
                </div>
            </div> {{-- End Row --}}
        </div>
    </section>
@endsection

@section('footer')
    {{-- Ini akan meng-include footer jika Anda punya partials/footer.blade.php --}}
    @include('partials.footer')
@endsection
