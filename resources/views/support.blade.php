@extends('layout')

@section('header')
    @include('partials.header')
@endsection

@section('content')
<section class="pt-lg-11 pt-md-5 pt-7 pb-7 pb-md-5 pb-md-12 bg-light-gray overflow-hidden">
  <div class="container-fluid">
    <div class="row mb-lg-7">
        <div class="col-lg-6 mb-7 mb-md-5 mb-lg-0">
        <h2 class="fw-normal text-lg-start text-center mb-4"><span class="fw-bold fs-16">Support Platform</span><br><span class="fs-10">keyperson.id</span></h2>
        <div class="d-flex justify-content-lg-start justify-content-center gap-3">
          <a class="btn btn-primary" href="https://wa.me/6282299668860?text=Nama%20%3A%0AEmail%20%3A%0ASupport%20%3A">Chat via WhatsApp</a>
          <a class="btn btn-outline-primary" href="mailto:kastaraparama.idn@gmail.com?subject=Support%20Request&body=Nama%20:%0AEmail%20:%0ASupport%20:">Send Email</a>
        </div>
        </div>
        <div class="col-lg-6">
          <p class="fs-4 mb-0 text-muted lh-lg text-justify mb-2">We are a passionate team from <b>PT Kastara Prama Nusantara</b>, dedicated to building meaningful digital solutions for people development and professional growth.</p>
          <p class="fs-4 mb-0 text-muted lh-lg text-justify">We believe in the power of connection — between individuals and the right professionals — to unlock potential and create positive change.</p>
        </div>
    </div>
  </div>
</section>


<section class="pt-5 pt-md-14 pt-lg-11 pb-9 pb-lg-5 pb-lg-12 border-bottom">
  <div class="container-fluid">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <h2 class="fs-15 fw-bolder mb-0 text-center mb-5 mb-md-12">
          Frequently asked questions
        </h2>
        <div class="accordion faq-accordion" id="accordionExample1">
          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button fs-5" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                What is Keyperson.id?
              </button>
            </h2>
            <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample1" style="">
              <div class="accordion-body">
                <p>Keyperson.id is a platform that helps you find and book appointments with professionals like counselors, mentors, and teachers — all appointments are automatically synced with your Google Calendar.</p>
              </div>
            </div>
          </div>
          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed  fs-5" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                Do I need a Google account to use Keyperson.id?
              </button>
            </h2>
            <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample1">
              <div class="accordion-body">
                <p>Yes. You must sign in with your Google account to book appointments, as our system uses Google Calendar to manage and sync your bookings.</p>
              </div>
            </div>
          </div>
          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed fs-5" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                Is there a cost to use the platform?
              </button>
            </h2>
            <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionExample1">
              <div class="accordion-body">
                <p>Browsing professionals is free, but booking an appointment requires payment. Prices are shown clearly before confirming any appointment.</p>
              </div>
            </div>
          </div>
          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed fs-5" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                How does the appointment system work?
              </button>
            </h2>
            <div id="collapseFour" class="accordion-collapse collapse" data-bs-parent="#accordionExample1">
              <div class="accordion-body">
                <p>Once you find the right professional, you can choose an available time slot and confirm the appointment. It will automatically appear in both your Google Calendar and the professional’s.</p>
              </div>
            </div>
          </div>
          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed fs-5" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                Can I cancel or reschedule an appointment?
              </button>
            </h2>
            <div id="collapseFive" class="accordion-collapse collapse" data-bs-parent="#accordionExample1">
              <div class="accordion-body">
                <p>Yes, but it depends on the cancellation policy of each professional. You’ll find the reschedule/cancel options in your dashboard after booking.</p>
              </div>
            </div>
          </div>
          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed fs-5" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                Who should I contact if I need help?
              </button>
            </h2>
            <div id="collapseSix" class="accordion-collapse collapse" data-bs-parent="#accordionExample1">
              <div class="accordion-body">
                <p>You can reach out to our support team via:</p>
                <ul>
                  <li>WhatsApp: <a href="https://wa.me/6282299668860?text=Nama%20%3A%0AEmail%20%3A%0ASupport%20%3A">Click here</a></li>
                  <li>Email: <a href="mailto:kastaraparama.idn@gmail.com?subject=Support%20Request&body=Nama%20:%0AEmail%20:%0ASupport%20:">kastaraparama.idn@gmail.com</a></li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection

@section('footer')
    @include('partials.footer')
@endsection