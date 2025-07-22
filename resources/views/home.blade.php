@extends('layout')

@section('header')
    @include('partials.header')
@endsection

@section('content')
<section class="hero-section text-bg-light position-relative overflow-hidden mb-0">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-xl-6">
                <div class="hero-content my-5 py-3 my-xl-0">
                    <h6 class="d-flex align-items-center gap-2 fs-4 fw-semibold mb-3 aos-init aos-animate" data-aos="fade-up" data-aos-delay="200" data-aos-duration="1000">
                        <i class="ti ti-rocket text-secondary fs-6"></i> keyperson.id
                    </h6>
                    <h1 class="fw-bolder mb-7 aos-init aos-animate" data-aos="fade-up" data-aos-delay="400" data-aos-duration="1000">
                        <span class="text-primary">keyPerson</span> – Find the Right Person, Set the Right Time
                    </h1>
                    <p class="fs-5 mb-5 text-dark fw-normal aos-init aos-animate text-justify" data-aos="fade-up" data-aos-delay="600" data-aos-duration="1000">
                        Keyperson.id is a platform that helps you connect with the right professionals — from counselors to mentors, coaches to teachers. We make it easy to search, book, and manage appointments with just a few clicks.
                    </p>
                    <p class="fs-5 mb-5 text-dark fw-normal aos-init aos-animate text-justify" data-aos="fade-up" data-aos-delay="600" data-aos-duration="1000">
                        All appointments you make are automatically synced to your Google Calendar, ensuring you never miss an important session.
                    </p>

                    <div class="d-sm-flex align-items-center gap-3 aos-init aos-animate" data-aos="fade-up" data-aos-delay="800" data-aos-duration="1000">
                        <a class="btn btn-primary px-5 py-6 btn-hover-shadow d-block mb-3 mb-sm-0" href="{{route('login')}}">Get startet</a>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 d-none d-xl-block">
                <div class="hero-img-slide position-relative bg-light p-4 rounded">
                <div class="d-flex flex-row">
                    <div>
                    <div class="banner-img-1 slideup">
                        <img src="../assets/images/hero-img/bannerimg1.png" alt="matdash-img" class="img-fluid">
                    </div>
                    <div class="banner-img-1 slideup">
                        <img src="../assets/images/hero-img/bannerimg1.png" alt="matdash-img" class="img-fluid">
                    </div>
                    </div>
                    <div>
                    <div class="banner-img-2 slideDown">
                        <img src="../assets/images/hero-img/bannerimg2.png" alt="matdash-img" class="img-fluid">
                    </div>
                    <div class="banner-img-2 slideDown">
                        <img src="../assets/images/hero-img/bannerimg2.png" alt="matdash-img" class="img-fluid">
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
