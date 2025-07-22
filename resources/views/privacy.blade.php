@extends('layout')

@section('header')
    @include('partials.header')
@endsection

@section('content')
<section class="pt-lg-10 pt-md-5 pt-7 pb-7 pb-md-5 pb-md-12 bg-light-gray overflow-hidden">
  <div class="container-fluid">
    <div class="card rounded-2 overflow-hidden">
      <div class="card-body p-4">
        <h2 class="fs-9 fw-semibold mt-3">PRIVACY POLICY</h2>
        <span class="badge text-bg-light mb-3">Effective Date: 22 July 2025</span>
        <p class="px-1 mb-0">Welcome to Keyperson.id, a platform operated by PT Kastara Prama Nusantara ("we", "us", or "our"). Your privacy is important to us. This Privacy Policy explains how we collect, use, disclose, and protect your personal information when you use our service.</p>
      </div>
      <div class="card-body border-top p-4 pt-3">
        <h2 class="fs-7 fw-semibold mb-3 mt-1">Information We Collect</h2>
        <p class="mb-3">When you use our service, we may collect the following data:</p>
        <ul class="my-3 ps-4">
            <li class="d-flex align-items-center gap-2"><span class="p-1 text-bg-dark rounded-circle"></span>Your name, email, and profile picture from your Google account (after login)</li>
            <li class="d-flex align-items-center gap-2"><span class="p-1 text-bg-dark rounded-circle"></span>Appointment data created or received via our platform</li>
            <li class="d-flex align-items-center gap-2"><span class="p-1 text-bg-dark rounded-circle"></span>Google Calendar access (used to schedule appointments on your behalf)</li>
            <li class="d-flex align-items-center gap-2"><span class="p-1 text-bg-dark rounded-circle"></span>Payment and transaction details related to booking appointments</li>
        </ul>

        <h2 class="fs-7 fw-semibold mb-3 mt-4">Use of Your Information</h2>
        <p class="mb-3">We use your data to:</p>
        <ul class="my-3 ps-4">
            <li class="d-flex align-items-center gap-2"><span class="p-1 text-bg-dark rounded-circle"></span>Authenticate and authorize your account via Google</li>
            <li class="d-flex align-items-center gap-2"><span class="p-1 text-bg-dark rounded-circle"></span>Enable appointment scheduling with professionals</li>
            <li class="d-flex align-items-center gap-2"><span class="p-1 text-bg-dark rounded-circle"></span>Sync appointments to your Google Calendar</li>
            <li class="d-flex align-items-center gap-2"><span class="p-1 text-bg-dark rounded-circle"></span>Process payments securely</li>
            <li class="d-flex align-items-center gap-2"><span class="p-1 text-bg-dark rounded-circle"></span>Provide user support and improve services</li>
        </ul>

        <h2 class="fs-7 fw-semibold mb-3 mt-4">Payment Information</h2>
        <p class="mb-3">We do not store your credit/debit card or bank account numbers. Payments are handled securely by our authorized third-party payment processor. We may store:</p>
        <ul class="my-3 ps-4">
            <li class="d-flex align-items-center gap-2"><span class="p-1 text-bg-dark rounded-circle"></span>Payment history</li>
            <li class="d-flex align-items-center gap-2"><span class="p-1 text-bg-dark rounded-circle"></span>Transaction amount</li>
            <li class="d-flex align-items-center gap-2"><span class="p-1 text-bg-dark rounded-circle"></span>Timestamp of transaction</li>
        </ul>

        <h2 class="fs-7 fw-semibold mb-3 mt-4">Sharing of Information</h2>
        <p class="mb-3">Your personal data is never sold or rented. It may only be shared in the following cases:</p>
        <ul class="my-3 ps-4">
            <li class="d-flex align-items-center gap-2"><span class="p-1 text-bg-dark rounded-circle"></span>With your consent</li>
            <li class="d-flex align-items-center gap-2"><span class="p-1 text-bg-dark rounded-circle"></span>To comply with legal requirements</li>
            <li class="d-flex align-items-center gap-2"><span class="p-1 text-bg-dark rounded-circle"></span>With our service providers for technical or security purposes</li>
        </ul>

        <h2 class="fs-7 fw-semibold mb-3 mt-4">Security</h2>
        <p class="mb-3">We apply industry-standard encryption and security practices to protect your personal information from unauthorized access.</p>

        <h2 class="fs-7 fw-semibold mb-3 mt-4">Your Rights</h2>
        <p class="mb-3">You can:</p>
        <ul class="my-3 ps-4">
            <li class="d-flex align-items-center gap-2"><span class="p-1 text-bg-dark rounded-circle"></span>View and edit your account data</li>
            <li class="d-flex align-items-center gap-2"><span class="p-1 text-bg-dark rounded-circle"></span>Delete your account upon request</li>
            <li class="d-flex align-items-center gap-2"><span class="p-1 text-bg-dark rounded-circle"></span>Revoke access from your Google Account settings</li>
        </ul>

        <h2 class="fs-7 fw-semibold mb-3 mt-4">Third-Party Services</h2>
        <p class="mb-3">Our application integrates with:</p>
        <ul class="my-3 ps-4">
            <li class="d-flex align-items-center gap-2"><span class="p-1 text-bg-dark rounded-circle"></span>Google Calendar API</li>
            <li class="d-flex align-items-center gap-2"><span class="p-1 text-bg-dark rounded-circle"></span>Google OAuth (Sign-in)</li>
            <li class="d-flex align-items-center gap-2"><span class="p-1 text-bg-dark rounded-circle"></span>Third-party payment gateway</li>
        </ul>
        <p class="mb-3">Use of these services is governed by their own privacy policies.</p>

        <h2 class="fs-7 fw-semibold mb-3 mt-4">Contact Us</h2>
        <p class="mb-3">If you have questions or feedback: <b>PT Kastara Prama Nusantara</b></p>
        <div class="p-3 bg-light rounded border-start border-2 border-primary">
          <h6 class="mb-0 fs-4 fw-semibold">Email: kastaraparama.idn@gmail.com
          </h6>
        </div>

        <p class="mb-0 mt-5">If you have read and understood our Privacy Policy,</p>
        <a class="fw-bolder" href="{{route('terms')}}">please proceed to the Terms and Conditions!</a>
      </div>
    </div>
  </div>
</section>
@endsection

@section('footer')
    @include('partials.footer')
@endsection
