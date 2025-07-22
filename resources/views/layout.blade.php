<!DOCTYPE html>
<html lang="en" dir="ltr" data-bs-theme="light" data-color-theme="Blue_Theme" data-layout="vertical">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <link rel="shortcut icon" type="image/png" href="{{asset('assets/images/logos/favicon.png')}}" />
  <link rel="stylesheet" href="{{asset('assets/libs/quill/dist/quill.snow.css')}}">
  <link rel="stylesheet" href="{{asset('assets/libs/@claviska/jquery-minicolors/jquery.minicolors.css')}}">

  <link rel="stylesheet" href="{{asset('assets/css/styles.css')}}" />

  <title>keyPerson</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-material-datetimepicker/2.7.1/css/bootstrap-material-datetimepicker.min.css">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link rel="stylesheet" href="{{asset('assets/libs/owl.carousel/dist/assets/owl.carousel.min.css')}}" />
  <script src="https://cdn.jsdelivr.net/npm/initial.js@1.0.0/initial.min.js"></script>

  <script>
    window.S3_CONFIG = {
        endpoint: "{{ rtrim(config('filesystems.disks.s3.endpoint'), '/') }}",
        bucket: "{{ config('filesystems.disks.s3.bucket') }}"
    };
  </script>
</head>

<body class="d-flex flex-column min-vh-100">
  <div class="preloader">
    <img src="{{asset('assets/images/logos/favicon.png')}}" alt="loader" class="lds-ripple img-fluid" />
  </div>

  @yield('header')

  <main class="flex-fill">
    @yield('top')

    @yield('content')
  </main>

  @yield('footer')

  <script src="{{asset('assets/js/vendor.min.js')}}"></script>
  <!-- Import Js Files -->
  <script src="{{asset('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js')}}"></script>
  <script src="{{asset('assets/libs/simplebar/dist/simplebar.min.js')}}"></script>
  <script src="{{asset('assets/js/theme/app.init.js')}}"></script>
  <script src="{{asset('assets/js/theme/theme.js')}}"></script>
  <script src="{{asset('assets/js/theme/app.min.js')}}"></script>
  <script src="{{asset('assets/js/theme/sidebarmenu.js')}}"></script>

  <script src="{{asset('assets/js/apps/chat.js')}}"></script>

  <script src="{{asset('assets/libs/quill/dist/quill.min.js')}}"></script>

  <script src="{{asset('assets/libs/jquery-asColor/dist/jquery-asColor.min.js')}}"></script>
  <script src="{{asset('assets/libs/jquery-asGradient/dist/jquery-asGradient.min.js')}}"></script>
  <script src="{{asset('assets/libs/jquery-asColorPicker/dist/jquery-asColorPicker.min.js')}}"></script>
  <script src="{{asset('assets/libs/@claviska/jquery-minicolors/jquery.minicolors.min.js')}}"></script>
  <script src="{{asset('assets/js/plugins/colorpicker-init.js')}}"></script>
  <!-- solar icons -->
  <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
  <script src="{{asset('assets/libs/owl.carousel/dist/owl.carousel.min.js')}}"></script>
  <script src="{{asset('assets/js/frontend-landingpage/homepage.js')}}"></script>

  <script src="{{asset('assets/js/extra-libs/moment/moment.min.js')}}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-material-datetimepicker/2.7.1/js/bootstrap-material-datetimepicker.min.js"></script>
  <script src="{{asset('assets/libs/jquery.repeater/jquery.repeater.min.js')}}"></script>
  <script src="{{asset('assets/js/forms/material-datepicker-init.js')}}"></script>


  @yield('script')
</body>

</html>