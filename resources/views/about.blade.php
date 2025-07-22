@extends('layout')

@section('header')
    @include('partials.header')
@endsection

@section('content')
<section class="pt-lg-11 pt-md-5 pt-7 pb-7 pb-md-5 pb-md-12 bg-light-gray overflow-hidden">
    <div class="container-fluid">
    <div class="row mb-lg-7">
        <div class="col-lg-7 mb-7 mb-md-5 mb-lg-0">
        <h2 class="fw-normal text-lg-start text-center mb-4"><span class="fw-bold fs-16">Get to know</span>
            <span class="fw-bolder fs-16">keyPerson.id</span><br>by PT. Kastara Prama Nusantara
        </h2>
        <div class="d-flex justify-content-lg-start justify-content-center gap-3">
            <a class="btn btn-primary" href="{{route('register_client')}}">Create an Organizer</a>
            <a class="btn btn-outline-primary" href="{{route('register_expert')}}">I am Professional</a>
        </div>
        </div>
        <div class="col-lg-5">
        <p class="fs-4 mb-0 text-muted lh-lg text-justify mb-2">Keyperson.id is a smart platform that connects you with the right professionals — from counselors and mentors to teachers and coaches — to support your personal or professional growth.</p>
        <p class="fs-4 mb-0 text-muted lh-lg text-justify">All appointments are scheduled online and automatically synced to both your Google Calendar and the professional’s, making the process simple, fast, and hassle-free</p>
        </div>
    </div>
    </div>
</section>

<section class="pt-5 pt-md-14 pt-lg-11 pb-9 pb-lg-5 pb-lg-12 border-bottom">
    <div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-9 text-center">
        <h2 class="fs-15 my-9 fw-bolder text-center lh-sm">
            Our Mission
        </h2>
        <p class="fs-5 mb-9 px-xl-11">
            To connect individuals and organizations with the right professionals, quickly and efficiently, through smart, integrated scheduling technology.
        </p>
        </div>
    </div>
    </div>
</section>

<section class="pt-5 pt-md-14 pt-lg-11 pb-9 pb-lg-5 pb-lg-12 border-bottom">
    <div class="container-fluid">
    <h2 class="fs-15 fw-bolder text-center mb-7 mb-md-5">Our Values</h2>
    <div class="row">
        <div class="col-lg-4 col-md-6">
            <div class="card bg-primary-subtle p-7 d-flex flex-column gap-3 align-items-start rounded">
            <div class="bg-white round-48 hstack justify-content-center rounded process-shadow">
                <img src="../assets/images/frontend-pages/icon-store.svg" alt="store">
            </div>
            <h4 class="fw-semibold mb-0">Ease of Access</h4>
            <p class="text-muted mb-0">Just sign in with your Google account and start scheduling instantly.</p>
            </div>
        </div>

        <div class="col-lg-4 col-md-6">
        <div class="card bg-success-subtle p-7 d-flex flex-column gap-3 align-items-start rounded">
            <div class="bg-white round-48 hstack justify-content-center rounded process-shadow">
                <img src="../assets/images/frontend-pages/icon-shapes.svg" alt="store">
            </div>
            <h4 class="fw-semibold mb-0">Transparency</h4>
            <p class="text-muted mb-0">Prices, schedules, and professional profiles are clearly displayed.</p>
        </div>
        </div>

        <div class="col-lg-4 col-md-6">
        <div class="card bg-light-subtle p-7 d-flex flex-column gap-3 align-items-start rounded">
            <div class="bg-white round-48 hstack justify-content-center rounded process-shadow">
                <img src="../assets/images/frontend-pages/icon-wave.svg" alt="store">
            </div>
            <h4 class="fw-semibold mb-0">Privacy & Security</h4>
            <p class="text-muted mb-0">Your data is protected and never shared without your explicit consent.</p>
        </div>
        </div>
    </div>
    </div>
</section>

<section class="bg-dark py-7 py-md-14 py-lg-11">
    <div class="container-fluid">
    <div class="row mb-7 mb-lg-0">
        <div class="col-sm-7">
        <h2 class="text-white fs-15 fw-bolder mb-lg-0 lh-sm">
            Who We Are
        </h2>
        </div>
        <div class="col-sm-5">
        <p class="fs-4 mb-0 text-white">
            Keyperson.id is developed and managed by: <br><span class="text-white fs-6 fw-bolder mb-3">PT Kastara Prama Nusantara</span>
        </p>
        </div>
    </div>
</section>
<section class="bg-primary py-3 ">
      <div class="container-fluid">
        <div class="d-flex align-items-center text-center justify-content-center flex-md-nowrap flex-wrap gap-3">
          <p class="mb-0 text-white fs-4">We are a technology team committed to building impactful digital solutions, particularly in the areas of human development, education, and professional services.</p>
        </div>
      </div>
    </section>
@endsection

@section('footer')
    @include('partials.footer')
@endsection
