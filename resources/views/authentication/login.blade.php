@extends('layout')

@section('content')
    <div class="position-relative overflow-hidden auth-bg min-vh-100 w-100 d-flex align-items-center justify-content-center">
        <div class="d-flex align-items-center justify-content-center w-100">
            <div class="row justify-content-center w-100 my-5 my-xl-0">
                <div class="col-md-9 d-flex flex-column justify-content-center">
                    <div class="card mb-0 bg-body auth-login m-auto w-100">
                        <div class="row gx-0">
                            <div class="col-xl-6 border-end">
                                <div class="row justify-content-center py-4">
                                    <div class="col-lg-11">
                                        <div class="card-body">
                                            <a href="{{ route('home') }}" class="text-nowrap logo-img d-block mb-4 w-100">
                                                <img src="{{ asset('assets/images/logos/key-person.png') }}" class="dark-logo" width="150" alt="Logo-Dark" />
                                            </a>
                                            <h2 class="lh-base mb-4 text-center">Let's get you signed in</h2>
                                            <div class="row">
                                                <div class="col-12">
                                                    <a class="btn btn-white shadow-sm text-dark link-primary border fw-semibold d-flex align-items-center justify-content-center rounded-1 py-6"
                                                        href="{{ route('google.login') }}" role="button">
                                                        <img src="{{ asset('assets/images/svgs/google-icon.svg') }}"
                                                            alt="matdash-img" class="img-fluid me-2" width="18"
                                                            height="18">
                                                        <span class="d-none d-xxl-inline-flex"> Sign in with </span>&nbsp;
                                                        Google
                                                    </a>

                                                </div>
                                            </div>
                                            <div class="position-relative text-center my-4">
                                                <p
                                                    class="mb-0 fs-12 px-3 d-inline-block bg-body z-index-5 position-relative">
                                                    Or sign in with
                                                    email
                                                </p>
                                                <span
                                                    class="border-top w-100 position-absolute top-50 start-50 translate-middle"></span>
                                            </div>
                                            <form action="{{ route('login_post') }}" method="POST"
                                                enctype="multipart/form-data">@csrf
                                                <div class="mb-3">
                                                    <label for="email" class="form-label">Gmail Address</label>
                                                    <input type="email"
                                                        class="form-control @error('email') is-invalid @enderror"
                                                        id="email" name="email" value="{{ old('email') }}"
                                                        placeholder="Enter your google email" aria-describedby="">
                                                    @error('email')
                                                        <div class="invalid-feedback">{{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                                <div class="mb-4">
                                                    <div class="d-flex align-items-center justify-content-between">
                                                        <label for="password" class="form-label">Password</label>
                                                        <a class="text-primary link-dark fs-2" href="">Forgot
                                                            Password ?</a>
                                                    </div>
                                                    <input type="password"
                                                        class="form-control @error('password') is-invalid @enderror"
                                                        id="password" name="password" value="{{ old('password') }}"
                                                        placeholder="Enter your password">
                                                    @error('password')
                                                        <div class="invalid-feedback">{{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                                <div class="d-flex align-items-center justify-content-between mb-4">
                                                    <div class="form-check">
                                                        <input
                                                            class="form-check-input primary {{ old('remember') ? 'checked' : '' }}"
                                                            type="checkbox" value="" id="remember" name="remember"
                                                            checked>
                                                        <label class="form-check-label text-dark" for="remember">
                                                            I agree to the <a class="fw-bolder" href="{{route('terms')}}">Terms Of Service</a> and <a class="fw-bolder" href="{{route('privacy')}}">Privacy Policy</a>
                                                        </label>
                                                    </div>
                                                </div>
                                                <button type="submit" class="btn btn-dark w-100 py-8 mb-4 rounded-1">Sign In</button>
                                                <div class="d-flex align-items-center justify-content-center">
                                                    <p class="fs-12 mb-0 fw-medium">Don’t have account?</p>
                                                    <a class="text-primary fw-bolder ms-2" href="{{ route('google.login') }}">Sign Up&nbsp;Google</a>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6 d-none d-xl-block">
                                <div class="row justify-content-center align-items-center h-100">
                                    <div class="col-lg-9">
                                        
                                        <div id="auth-login" class="carousel slide auth-carousel" data-bs-ride="carousel">
                                            <div class="carousel-indicators">
                                                <button type="button" data-bs-target="#auth-login" data-bs-slide-to="0"
                                                    class="active" aria-current="true" aria-label="Slide 1"></button>
                                                <button type="button" data-bs-target="#auth-login" data-bs-slide-to="1"
                                                    aria-label="Slide 2"></button>
                                                <button type="button" data-bs-target="#auth-login" data-bs-slide-to="2"
                                                    aria-label="Slide 3"></button>
                                            </div>
                                            <div class="carousel-inner">
                                                <div class="carousel-item active">
                                                    <div
                                                        class="d-flex align-items-center justify-content-center w-100 h-100 flex-column gap-9 text-center">
                                                        <img src="{{ asset('image/choose-expert.png') }}"
                                                            alt="login-side-img" width="300" class="img-fluid" />
                                                        <h4 class="mb-0">Choose profesional expert</h4>
                                                        <p class="fs-12 mb-0">Tentukan expert yang sedang di butuhkan,
                                                            kenali profile dan jadwal available nya</p>
                                                    </div>
                                                </div>
                                                <div class="carousel-item">
                                                    <div
                                                        class="d-flex align-items-center justify-content-center w-100 h-100 flex-column gap-9 text-center">
                                                        <img src="{{ asset('image/complate-payment.png') }}"
                                                            alt="login-side-img" width="300" class="img-fluid" />
                                                        <h4 class="mb-0">Payment and make Appointment</h4>
                                                        <p class="fs-12 mb-0">Selesaikan pembayaran lalu buat janji dengan
                                                            profesional yang di butuhkan</p>
                                                    </div>
                                                </div>
                                                <div class="carousel-item">
                                                    <div
                                                        class="d-flex align-items-center justify-content-center w-100 h-100 flex-column gap-9 text-center">
                                                        <img src="{{ asset('image/confirm-schedule.png') }}"
                                                            alt="login-side-img" width="300" class="img-fluid" />
                                                        <h4 class="mb-0">Confirmation Schedule with Expert</h4>
                                                        <p class="fs-12 mb-0">Setelah Appointment terbuah lalu konfirmasi
                                                            dengan expert dan mungkin bisa berubah Schedule.</p>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>



                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
