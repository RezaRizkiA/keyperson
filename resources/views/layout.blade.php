<!DOCTYPE html>
<html lang="en" dir="ltr" data-bs-theme="light" data-color-theme="Blue_Theme" data-layout="vertical">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <link rel="shortcut icon" type="image/png" href="{{asset('assets/images/logos/favicon.png')}}" />
  <link rel="stylesheet" href="{{asset('assets/css/styles.css')}}" />

  <title>MeetPro</title>
  <link rel="stylesheet" href="{{asset('assets/libs/owl.carousel/dist/assets/owl.carousel.min.css')}}" />
</head>

<body>
  <div class="preloader">
    <img src="{{asset('assets/images/logos/favicon.png')}}" alt="loader" class="lds-ripple img-fluid" />
  </div>

  @include('header')

  <div class="main-wrapper overflow-hidden">
    @yield('top')
    
    @yield('content')
  </div>

  <footer class="bg-dark">
    <div class="container-fluid">
      <div class="d-flex justify-content-between flex-md-nowrap flex-wrap py-13 border-top border-dark-subtle">
        <div class="d-flex gap-3">
          <img src="{{asset('assets/images/logos/logo-icon-white.svg')}}" alt="logo">
          <p class="text-white opacity-50 mb-0">All rights reserved {{ date('Y') }}</p>
        </div>
        <div>
          <p class="text-white mb-0">
            <span class="opacity-50">Produced by</span>
            <a href="" class="text-white link-primary">Kastara Parama Nusantara</a>
          </p>
        </div>
      </div>
    </div>
  </footer>

  <a href="javascript:void(0)" class="top-btn btn btn-primary d-flex align-items-center justify-content-center round-54 p-0 rounded-circle">
    <i class="ti ti-arrow-up fs-7"></i>
  </a>

  <script src="{{asset('assets/js/vendor.min.js')}}"></script>
  <!-- Import Js Files -->
  <script src="{{asset('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js')}}"></script>
  <script src="{{asset('assets/libs/simplebar/dist/simplebar.min.js')}}"></script>
  <script src="{{asset('assets/js/theme/app.init.js')}}"></script>
  <script src="{{asset('assets/js/theme/theme.js')}}"></script>
  <script src="{{asset('assets/js/theme/app.min.js')}}"></script>

  <!-- solar icons -->
  <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
  <script src="{{asset('assets/libs/owl.carousel/dist/owl.carousel.min.js')}}"></script>
  <script src="{{asset('assets/js/frontend-landingpage/homepage.js')}}"></script>
</body>

</html>