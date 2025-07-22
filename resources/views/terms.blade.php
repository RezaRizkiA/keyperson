@extends('layout')

@section('header')
    @include('partials.header')
@endsection

@section('content')
<section class="pt-lg-10 pt-md-5 pt-7 pb-7 pb-md-5 pb-md-12 bg-light-gray overflow-hidden">
  <div class="container-fluid">
    <div class="card rounded-2 overflow-hidden">
      <div class="card-body p-4">
        <h2 class="fs-9 fw-semibold mt-3">TERMS OF SERVICE</h2>
        <span class="badge text-bg-light mb-3">Effective Date: 22 July 2025</span>
        <p class="px-1 mb-0">These Terms of Service ("Terms") govern your use of Keyperson.id, owned and operated by PT Kastara Prama Nusantara. By accessing or using the platform, you agree to these Terms.</p>
      </div>
      <div class="card-body border-top p-4 pt-3">
        <h2 class="fs-7 fw-semibold mb-3 mt-1">Description of Service</h2>
        <p class="mb-3">Keyperson.id is an online platform that allows users to find professionals (e.g., counselors, mentors, teachers) and schedule appointments. Appointments are automatically integrated with Google Calendar.</p>

        <h2 class="fs-7 fw-semibold mb-3 mt-4">Google Authentication</h2>
        <p class="mb-3">To use the platform, you must log in using your Google account. You grant us permission to:</p>
        <ul class="my-3 ps-4">
            <li class="d-flex align-items-center gap-2"><span class="p-1 text-bg-dark rounded-circle"></span>View your Google profile (name, email, photo)</li>
            <li class="d-flex align-items-center gap-2"><span class="p-1 text-bg-dark rounded-circle"></span>Manage your calendar events (create, update, delete)</li>
        </ul>
        <p class="mb-3">These permissions are only used to support appointment scheduling and calendar synchronization.</p>

        <h2 class="fs-7 fw-semibold mb-3 mt-4">Paid Appointments</h2>
        <ul class="my-3 ps-4">
            <li class="d-flex align-items-center gap-2"><span class="p-1 text-bg-dark rounded-circle"></span>Scheduling appointments with professionals <b>requires payment</b>.</li>
            <li class="d-flex align-items-center gap-2"><span class="p-1 text-bg-dark rounded-circle"></span>Fees are clearly displayed before you complete a booking.</li>
            <li class="d-flex align-items-center gap-2"><span class="p-1 text-bg-dark rounded-circle"></span>Appointments are only confirmed after successful payment.</li>
            <li class="d-flex align-items-center gap-2"><span class="p-1 text-bg-dark rounded-circle"></span>All payments are non-refundable unless otherwise stated in individual service terms or based on the professional’s cancellation policy.</li>
        </ul>

        <h2 class="fs-7 fw-semibold mb-3 mt-4">User Responsibilities</h2>
        <p class="mb-3">You agree to:</p>
        <ul class="my-3 ps-4">
            <li class="d-flex align-items-center gap-2"><span class="p-1 text-bg-dark rounded-circle"></span>Provide accurate information when registering or booking</li>
            <li class="d-flex align-items-center gap-2"><span class="p-1 text-bg-dark rounded-circle"></span>Not misuse the platform for spam, fraud, or impersonation</li>
            <li class="d-flex align-items-center gap-2"><span class="p-1 text-bg-dark rounded-circle"></span>Honor the appointment you book, or cancel it responsibly</li>
        </ul>

        <h2 class="fs-7 fw-semibold mb-3 mt-4">Limitation of Liability</h2>
        <p class="mb-3">Keyperson.id is a platform facilitator. We do not control or guarantee the quality or conduct of professionals listed on the platform.<br>We are not responsible for:</p>
        <ul class="my-3 ps-4">
            <li class="d-flex align-items-center gap-2"><span class="p-1 text-bg-dark rounded-circle"></span>Missed or canceled appointments</li>
            <li class="d-flex align-items-center gap-2"><span class="p-1 text-bg-dark rounded-circle"></span>Disputes between users and professionals</li>
            <li class="d-flex align-items-center gap-2"><span class="p-1 text-bg-dark rounded-circle"></span>Calendar integration failures due to third-party system issues</li>
        </ul>

        <h2 class="fs-7 fw-semibold mb-3 mt-4">Termination</h2>
        <p class="mb-3">We reserve the right to suspend or delete accounts that violate our Terms or abuse our services.</p>

        <h2 class="fs-7 fw-semibold mb-3 mt-4">Intellectual Property</h2>
        <p class="mb-3">All content, logos, branding, and software are owned by PT Kastara Prama Nusantara. Unauthorized use is prohibited.</p>

        <h2 class="fs-7 fw-semibold mb-3 mt-4">Modifications</h2>
        <p class="mb-3">We may update these Terms at any time. Continued use of the platform after changes means you accept the revised Terms.</p>

        <h2 class="fs-7 fw-semibold mb-3 mt-4">Contact Us</h2>
        <p class="mb-3">If you have questions or feedback: <b>PT Kastara Prama Nusantara</b></p>
        <div class="p-3 bg-light rounded border-start border-2 border-primary">
          <h6 class="mb-0 fs-4 fw-semibold">Email: kastaraparama.idn@gmail.com
          </h6>
        </div>

        <p class="mb-0 mt-5">If you have read and understood our Terms and Conditions</p>
        <a class="fw-bolder" href="{{route('privacy')}}">please continue to the Privacy Policy!</a>
      </div>
    </div>
  </div>
</section>
@endsection

@section('footer')
    @include('partials.footer')
@endsection
