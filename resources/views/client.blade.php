@extends('layout')

@section('top')
<section class="py-5 bg-light-gray">
    <div class="container-fluid">
      <div class="d-flex justify-content-between flex-md-nowrap flex-wrap">
        <h2 class="fs-15 fw-bolder ">
          Expert Help in One Place
        </h2>
        <div class="d-flex align-items-center gap-6">
          <a href="{{route('expert')}}" class="text-muted fw-bolder link-primary fs-3 text-uppercase">
            MeetPro
          </a>
          <iconify-icon icon="solar:alt-arrow-right-outline" class="fs-5 text-muted"></iconify-icon>
          <a href="#" class="text-primary link-primary fw-bolder fs-3 text-uppercase">
            Client #1
          </a>
        </div>
      </div>
    </div>
  </section>
@endsection

@section('content')
<section class="bg-light-gray pb-3 pb-md-7 pb-lg-12">
    <div class="container-fluid">
      <div class="card data-shadow rounded-3 overflow-hidden mb-7">
        <div class="row">
          <div class="col-lg-6 order-last order-lg-first">
            <div class="p-7 p-lg-5 flex-grow-1">
              <div class="py-lg-4 d-flex flex-column gap-3">
                <a href="{{route('expert')}}">
                  <h2 class="fw-bolder fs-8">
                      Kolaborasi Cerdas untuk Masa Depan Siswa
                  </h2>
                </a>
                <p class="">
                  Di era pendidikan yang terus berkembang, kebutuhan akan bimbingan yang tepat — baik secara akademik, emosional, maupun karakter — menjadi semakin penting. 
                  <br><br>Dengan platform ini kami percaya kolaborasi antara sekolah, keluarga, dan para profesional merupakan kunci untuk menciptakan lingkungan belajar yang sehat.
                </p>
                <div class="d-flex justify-content-end align-items-center">
                  <div class="d-flex gap-2">
                    <i class="ti ti-circle"></i>
                    <p class="mb-0 fs-1 fw-semibold text-dark">Tue, May 2</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-6 order-first order-lg-last">
            <div class="blog-bg d-flex flex-column justify-content-between p-9 h-100 flex-grow-1">
              <img src="{{asset('assets/images/profile/user-6.jpg')}}" alt="user" width="44" height="44" class="rounded-circle">
              <div class="d-flex justify-content-end">
                <p class="fs-2 py-1 px-2 bg-white rounded-1 fw-semibold mb-0 text-dark">29 profesional expert
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">

        <div class="col-lg-4 col-md-6">
          <div class="card rounded-3 overflow-hidden">
            <a href="{{route('expert')}}" class="position-relative">
              <img src="{{asset('assets/images/frontend-pages/blog-1.jpg')}}" alt="blog image" class="w-100 img-fluid">
              <div class="position-absolute bottom-0 ms-7 mb-n9">
                <img src="{{asset('assets/images/profile/user-3.jpg')}}" alt="user" class="rounded-circle" width="44px" height="44px">
              </div>
            </a>
            <div class="mt-7 px-7 pb-7 h-100">
              <div class="d-flex gap-3 flex-column h-100 justify-content-between">
                  <div class="d-flex">
                      <p class="fs-2 px-2 rounded-pill bg-muted bg-opacity-25 text-dark mb-0">
                          Medis & Kesehatan
                      </p>
                    </div>
                <a href="{{route('expert')}}" class="fs-5 fw-bolder">
                  Solusi kesehatan bersama tenaga medis terpercaya
                </a>
                <div class="d-flex justify-content-between align-items-center">
                  <div class="d-flex gap-9">
                      <div class="d-flex gap-1">
                          <i class="ti ti-message fs-5 text-dark"></i>
                          <p class="mb-0 fs-2 fw-bold">3</p>
                      </div>
                      <div class="d-flex gap-1">
                          <i class="ti ti-users fs-5 text-dark"></i>
                          <p class="mb-0 fs-2 fw-bold">14 profesional expert</p>
                      </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6">
          <div class="card rounded-3 overflow-hidden">
            <a href="{{route('expert')}}" class="position-relative">
              <img src="{{asset('assets/images/frontend-pages/blog-2.jpg')}}" alt="blog image" class="w-100 img-fluid">
              <div class="position-absolute bottom-0 ms-7 mb-n9">
                <img src="{{asset('assets/images/profile/user-1.jpg')}}" alt="user" class="rounded-circle" width="44px" height="44px">
              </div>
            </a>
            <div class="mt-7 px-7 pb-7 h-100">
              <div class="d-flex gap-3 flex-column h-100 justify-content-between">
                <div class="d-flex">
                  <p class="fs-2 px-2 rounded-pill bg-muted bg-opacity-25 text-dark mb-0">
                      Kesehatan Mental & Konseling
                  </p>
                </div>
                <a href="{{route('expert')}}" class="fs-5 fw-bolder">
                  Ruang aman untuk bicara, pulih, dan bertumbuh
                </a>
                <div class="d-flex justify-content-between align-items-center">
                  <div class="d-flex gap-9">
                      <div class="d-flex gap-1">
                          <i class="ti ti-message fs-5 text-dark"></i>
                          <p class="mb-0 fs-2 fw-bold">3</p>
                      </div>
                      <div class="d-flex gap-1">
                          <i class="ti ti-users fs-5 text-dark"></i>
                          <p class="mb-0 fs-2 fw-bold">14 profesional expert</p>
                      </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6">
          <div class="card rounded-3 overflow-hidden">
            <a href="{{route('expert')}}" class="position-relative">
              <img src="{{asset('assets/images/frontend-pages/blog-3.jpg')}}" alt="blog image" class="w-100 img-fluid">
              <div class="position-absolute bottom-0 ms-7 mb-n9">
                <img src="{{asset('assets/images/profile/user-5.jpg')}}" alt="user" class="rounded-circle" width="44px" height="44px">
              </div>
            </a>
            <div class="mt-7 px-7 pb-7 h-100">
              <div class="d-flex gap-3 flex-column h-100 justify-content-between">
                <div class="d-flex">
                  <p class="fs-2 px-2 rounded-pill bg-muted bg-opacity-25 text-dark mb-0">
                      Pendidikan & Pengajaran
                  </p>
                </div>
                <a href="{{route('expert')}}" class="fs-5 fw-bolder">
                  Belajar lebih fokus, capai potensi terbaikmu
                </a>
                <div class="d-flex justify-content-between align-items-center">
                  <div class="d-flex gap-9">
                      <div class="d-flex gap-1">
                          <i class="ti ti-message fs-5 text-dark"></i>
                          <p class="mb-0 fs-2 fw-bold">3</p>
                      </div>
                      <div class="d-flex gap-1">
                          <i class="ti ti-users fs-5 text-dark"></i>
                          <p class="mb-0 fs-2 fw-bold">14 profesional expert</p>
                      </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-4 col-md-6">
          <div class="card rounded-3 overflow-hidden">
            <a href="{{route('expert')}}" class="position-relative">
              <img src="{{asset('assets/images/frontend-pages/blog-4.jpg')}}" alt="blog image" class="w-100 img-fluid">
              <div class="position-absolute bottom-0 ms-7 mb-n9">
                <img src="{{asset('assets/images/profile/user-1.jpg')}}" alt="user" class="rounded-circle" width="44px" height="44px">
              </div>
            </a>
            <div class="mt-7 px-7 pb-7 h-100">
              <div class="d-flex gap-3 flex-column h-100 justify-content-between">
                <div class="d-flex">
                  <p class="fs-2 px-2 rounded-pill bg-muted bg-opacity-25 text-dark mb-0">
                      Keuangan & Hukum
                  </p>
                </div>
                <a href="{{route('expert')}}" class="fs-5 fw-bolder">
                  Lindungi aset dan langkahmu dengan penasihat ahli.
                </a>
                <div class="d-flex justify-content-between align-items-center">
                  <div class="d-flex gap-9">
                      <div class="d-flex gap-1">
                          <i class="ti ti-message fs-5 text-dark"></i>
                          <p class="mb-0 fs-2 fw-bold">3</p>
                      </div>
                      <div class="d-flex gap-1">
                          <i class="ti ti-users fs-5 text-dark"></i>
                          <p class="mb-0 fs-2 fw-bold">14 profesional expert</p>
                      </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6">
          <div class="card rounded-3 overflow-hidden">
            <a href="{{route('expert')}}" class="position-relative">
              <img src="{{asset('assets/images/frontend-pages/blog-5.jpg')}}" alt="blog image" class="w-100 img-fluid">
              <div class="position-absolute bottom-0 ms-7 mb-n9">
                <img src="{{asset('assets/images/profile/user-3.jpg')}}" alt="user" class="rounded-circle" width="44px" height="44px">
              </div>
            </a>
            <div class="mt-7 px-7 pb-7 h-100">
              <div class="d-flex gap-3 flex-column h-100 justify-content-between">
                <div class="d-flex">
                  <p class="fs-2 px-2 rounded-pill bg-muted bg-opacity-25 text-dark mb-0">
                      Bisnis & Manajemen
                  </p>
                </div>
                <a href="{{route('expert')}}" class="fs-5 fw-bolder">
                  Bangun bisnis lebih kuat, bersama praktisi berpengalaman
                </a>
                <div class="d-flex justify-content-between align-items-center">
                  <div class="d-flex gap-9">
                      <div class="d-flex gap-1">
                          <i class="ti ti-message fs-5 text-dark"></i>
                          <p class="mb-0 fs-2 fw-bold">3</p>
                      </div>
                      <div class="d-flex gap-1">
                          <i class="ti ti-users fs-5 text-dark"></i>
                          <p class="mb-0 fs-2 fw-bold">14 profesional expert</p>
                      </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6">
          <div class="card rounded-3 overflow-hidden">
            <a href="{{route('expert')}}" class="position-relative">
              <img src="{{asset('assets/images/frontend-pages/blog-6.jpg')}}" alt="blog image" class="w-100 img-fluid">
              <div class="position-absolute bottom-0 ms-7 mb-n9">
                <img src="{{asset('assets/images/profile/user-2.jpg')}}" alt="user" class="rounded-circle" width="44px" height="44px">
              </div>
            </a>
            <div class="mt-7 px-7 pb-7 h-100">
              <div class="d-flex gap-3 flex-column h-100 justify-content-between">
                <div class="d-flex">
                  <p class="fs-2 px-2 rounded-pill bg-muted bg-opacity-25 text-dark mb-0">
                      Teknologi & Data
                  </p>
                </div>
                <a href="{{route('expert')}}" class="fs-5 fw-bolder">
                  Cerdas berteknologi, tepat sasaran bersama ahlinya
                </a>
                <div class="d-flex justify-content-between align-items-center">
                  <div class="d-flex gap-9">
                      <div class="d-flex gap-1">
                          <i class="ti ti-message fs-5 text-dark"></i>
                          <p class="mb-0 fs-2 fw-bold">3</p>
                      </div>
                      <div class="d-flex gap-1">
                          <i class="ti ti-users fs-5 text-dark"></i>
                          <p class="mb-0 fs-2 fw-bold">14 profesional expert</p>
                      </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-4 col-md-6">
          <div class="card rounded-3 overflow-hidden">
            <a href="{{route('expert')}}" class="position-relative">
              <img src="{{asset('assets/images/frontend-pages/blog-7.jpg')}}" alt="blog image" class="w-100 img-fluid">
              <div class="position-absolute bottom-0 ms-7 mb-n9">
                <img src="{{asset('assets/images/profile/user-5.jpg')}}" alt="user" class="rounded-circle" width="44px" height="44px">
              </div>
            </a>
            <div class="mt-7 px-7 pb-7 h-100">
              <div class="d-flex gap-3 flex-column h-100 justify-content-between">
                <div class="d-flex">
                  <p class="fs-2 px-2 rounded-pill bg-muted bg-opacity-25 text-dark mb-0">
                      Marketing & Branding
                  </p>
                </div>
                <a href="{{route('expert')}}" class="fs-5 fw-bolder">
                  Naikkan visibilitas dan konversi dengan strategi yang tepat
                </a>
                <div class="d-flex justify-content-between align-items-center">
                  <div class="d-flex gap-9">
                      <div class="d-flex gap-1">
                          <i class="ti ti-message fs-5 text-dark"></i>
                          <p class="mb-0 fs-2 fw-bold">3</p>
                      </div>
                      <div class="d-flex gap-1">
                          <i class="ti ti-users fs-5 text-dark"></i>
                          <p class="mb-0 fs-2 fw-bold">14 profesional expert</p>
                      </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6">
          <div class="card rounded-3 overflow-hidden">
            <a href="{{route('expert')}}" class="position-relative">
              <img src="{{asset('assets/images/frontend-pages/blog-8.jpg')}}" alt="blog image" class="w-100 img-fluid">
              <div class="position-absolute bottom-0 ms-7 mb-n9">
                <img src="{{asset('assets/images/profile/user-2.jpg')}}" alt="user" class="rounded-circle" width="44px" height="44px">
              </div>
            </a>
            <div class="mt-7 px-7 pb-7 h-100">
              <div class="d-flex gap-3 flex-column h-100 justify-content-between">
                <div class="d-flex">
                  <p class="fs-2 px-2 rounded-pill bg-muted bg-opacity-25 text-dark mb-0">
                      Keluarga & Parenting
                  </p>
                </div>
                <a href="{{route('expert')}}" class="fs-5 fw-bolder">
                  Karena setiap keluarga layak mendapat arahan terbaik
                </a>
                <div class="d-flex justify-content-between align-items-center">
                  <div class="d-flex gap-9">
                      <div class="d-flex gap-1">
                          <i class="ti ti-message fs-5 text-dark"></i>
                          <p class="mb-0 fs-2 fw-bold">3</p>
                      </div>
                      <div class="d-flex gap-1">
                          <i class="ti ti-users fs-5 text-dark"></i>
                          <p class="mb-0 fs-2 fw-bold">14 profesional expert</p>
                      </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6">
          <div class="card rounded-3 overflow-hidden">
            <a href="{{route('expert')}}" class="position-relative">
              <img src="{{asset('assets/images/frontend-pages/blog-9.jpg')}}" alt="blog image" class="w-100 img-fluid">
              <div class="position-absolute bottom-0 ms-7 mb-n9">
                <img src="{{asset('assets/images/profile/user-1.jpg')}}" alt="user" class="rounded-circle" width="44px" height="44px">
              </div>
            </a>
            <div class="mt-7 px-7 pb-7 h-100">
              <div class="d-flex gap-3 flex-column h-100 justify-content-between">
                <div class="d-flex">
                  <p class="fs-2 px-2 rounded-pill bg-muted bg-opacity-25 text-dark mb-0">
                      Agama & Spiritualitas
                  </p>
                </div>
                <a href="{{route('expert')}}" class="fs-5 fw-bolder">
                  Tenangkan hati, mantapkan langkah spiritual
                </a>
                <div class="d-flex justify-content-between align-items-center">
                  <div class="d-flex gap-9">
                      <div class="d-flex gap-1">
                          <i class="ti ti-message fs-5 text-dark"></i>
                          <p class="mb-0 fs-2 fw-bold">3</p>
                      </div>
                      <div class="d-flex gap-1">
                          <i class="ti ti-users fs-5 text-dark"></i>
                          <p class="mb-0 fs-2 fw-bold">14 profesional expert</p>
                      </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="bg-primary py-lg-11 py-5 position-relative">
    <div class="position-absolute top-50 start-0 translate-middle-y">
      {{-- <img src="{{asset('assets/images/frontend-pages/screenshot-1.png')}}" alt="image" class="d-xxl-block d-none"> --}}
    </div>
    <div class="container-fluid">
      <div class="row justify-content-center">
        <div class="col-lg-910 text-center">
          <a class="round-56 mx-auto rounded-2 shadow bg-white hstack justify-content-center" href="">
            <img src="{{asset('assets/images/logos/logo-icon.svg')}}" alt="logo">
          </a>
          <h2 class="fs-15 my-9 fw-bolder text-white text-center lh-sm">
              Tumbuh Kembang Siswa Bersama Para Ahli
          </h2>
          <p class="text-white fs-5 mb-9 px-xl-11">
              Manfaatkan layanan ini untuk berkonsultasi, mendapatkan pendampingan, atau mencari solusi terbaik demi masa depan siswa yang lebih cerah dan seimbang.
          </p>
          <a href="../main/authentication-register.html" class="btn btn-outline-light">
            Register
          </a>
        </div>
      </div>
    </div>
    <div class="position-absolute top-50 end-0 translate-middle-y">
      {{-- <img src="{{asset('assets/images/frontend-pages/screenshot-2.png')}}" alt="image" class="d-xxl-block d-none"> --}}
    </div>
  </section>
@endsection