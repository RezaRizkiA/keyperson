@extends('layout')

@section('top')
    <section class="py-4 py-md-5 bg-light-gray">
        <div class="container-fluid">
            <div class="d-flex justify-content-between flex-md-nowrap flex-wrap">
                <h2 class="fs-13 fw-bolder ">
                    User Profile
                </h2>
                <div class="d-flex align-items-center gap-6">
                    <a href="{{ route('home') }}" class="text-muted fw-bolder link-primary fs-3 text-uppercase">
                        Home
                    </a>
                    <iconify-icon icon="solar:alt-arrow-right-outline" class="fs-5 text-muted"></iconify-icon>
                    <a href="#" class="text-primary link-primary fw-bolder fs-3 text-uppercase">
                        User
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('content')
    <section class="pt-5 pt-md-14 pb-4 pb-md-5">
        <div class="container-fluid">
            <div class="card overflow-hidden border-1">
                <div class="card-header py-0 px-2">
                    <div class="task-container-header d-flex justify-content-end py-0">
                        <div class="dropdown">
                            <a class="text-dark fs-6 nav-icon-hover" href="javascript:void(0)" role="button"
                                data-bs-toggle="dropdown" aria-expanded="true">
                                <i class="ti ti-dots"></i>
                            </a>
                            <ul class="dropdown-menu"
                                style="position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate(0px, 30px);"
                                data-popper-placement="bottom-start">
                                <li><a class="dropdown-item d-flex align-items-center gap-2"
                                        href="{{ route('update_profile') }}"><span><i
                                                class="ti ti-settings fs-4"></i></span>Setting</a></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit"
                                            class="dropdown-item d-flex align-items-center gap-2 border-top text-danger">
                                            <span><i class="ti ti-logout fs-4"></i></span>Sign Out
                                        </button>
                                    </form>
                                </li>

                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0 m-0">
                    <img src="{{ asset('image/banner-detail-expert.jpg') }}" alt="matdash-img" class="img-fluid">
                    <div class="row align-items-center m-0 p-0">
                        <div class="col-lg-4 order-lg-1 order-2">
                            <div class="d-flex align-items-center justify-content-around m-4">
                                <div class="">
                                    <h4 class="mb-0 fw-semibold lh-1 d-flex justify-content-start"><i
                                            class="ti ti-users fs-6 d-block me-2"></i> 9</h4>
                                    <p class="mb-0 ">Users Member</p>
                                </div>

                                <div class="">
                                    <h4 class="mb-0 fw-semibold lh-1 d-flex justify-content-start"><i
                                            class="ti ti-file-description fs-6 d-block me-2"></i> 10</h4>
                                    <p class="mb-0 ">Appointment</p>
                                </div>

                                <div class="">
                                    <h4 class="mb-0 fw-semibold lh-1 d-flex justify-content-start"><i
                                            class="ti ti-user-check fs-6 d-block me-2"></i> 7</h4>
                                    <p class="mb-0 ">Completed</p>
                                </div>

                            </div>
                        </div>
                        <div class="col-lg-4 mt-n3 order-lg-2 order-1">
                            <div class="mt-n5">
                                <div class="d-flex align-items-center justify-content-center mb-2">
                                    <div class="d-flex align-items-center justify-content-center round-110">
                                        <div class="border border-4 border-white rounded-circle overflow-hidden"
                                            style="width: 110px; height: 110px;">
                                            <img src="{{ urlpathSTORAGE(Auth::user()->picture) }}" alt="profile-img"
                                                class="w-100 h-100 object-fit-cover rounded-circle">
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <h5 class="mb-0">{{ Auth::user()->name }}</h5>
                                    <p class="mb-0">IT Developer</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 order-last">
                            <ul
                                class="list-unstyled d-flex align-items-center justify-content-center justify-content-lg-end my-3 mx-4 gap-3">
                                @if (Auth::user()->expert != null)
                                    @php
                                        $socialConfig = [
                                            'facebook' => ['class' => 'btn-primary', 'icon' => 'ti ti-brand-facebook'],
                                            'instagram' => ['class' => 'btn-secondary','icon' => 'ti ti-brand-instagram',],
                                            'youtube' => ['class' => 'btn-danger', 'icon' => 'ti ti-brand-youtube'],
                                            'linkedin' => ['class' => 'btn-info', 'icon' => 'ti ti-brand-linkedin'],
                                        ];
                                        $expert = Auth::user()->expert ?? null;
                                        $socials = $expert->socials;
                                    @endphp

                                    @foreach ($socials as $sosmed)
                                        @php
                                            $key = $sosmed['key'] ?? '';
                                            $val = $sosmed['value'] ?? '#';
                                            $btnClass = $socialConfig[$key]['class'] ?? 'btn-dark';
                                            $iconClass = $socialConfig[$key]['icon'] ?? 'ti ti-link';
                                        @endphp
                                        <li>
                                            <a class="d-flex align-items-center justify-content-center btn {{ $btnClass }} p-2 fs-4 rounded-circle"
                                                href="{{ $val }}" target="_blank" width="30" height="30">
                                                <i class="{{ $iconClass }}"></i>
                                            </a>
                                        </li>
                                    @endforeach
                                @endif
                                @if (Auth::user()->client != null)
                                    <li class="">
                                        <a href="{{ route('home_client', Auth::user()->client->slug_page) }}"
                                            class="btn btn-outline-primary text-nowrap py-2">My Page</a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                    <ul class="nav nav-pills user-profile-tab justify-content-end mt-2 bg-primary-subtle rounded-2 rounded-top-0"
                        id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active hstack gap-2 rounded-0 fs-12 py-6" id="pills-appointment-tab"
                                data-bs-toggle="pill" data-bs-target="#pills-appointment" type="button" role="tab"
                                aria-controls="pills-appointment" aria-selected="true">
                                <i class="ti ti-user-circle fs-5"></i>
                                <span class="d-none d-md-block">Appointment</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link hstack gap-2 rounded-0 fs-12 py-6" id="pills-members-tab"
                                data-bs-toggle="pill" data-bs-target="#pills-members" type="button" role="tab"
                                aria-controls="pills-members" aria-selected="false">
                                <i class="ti ti-users fs-5"></i>
                                <span class="d-none d-md-block">User Members</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link hstack gap-2 rounded-0 fs-12 py-6" id="pills-calender-tab"
                                data-bs-toggle="pill" data-bs-target="#pills-calender" type="button" role="tab"
                                aria-controls="pills-calender" aria-selected="false">
                                <i class="ti ti-photo fs-5"></i>
                                <span class="d-none d-md-block">Calender</span>
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="pills-appointment" role="tabpanel"
                    aria-labelledby="pills-appointment-tab" tabindex="0">
                    <div class="card overflow-hidden chat-application border">
                        <div class="d-flex w-100">
                            <div class="min-width-200">
                                <div class="border-end user-chat-box h-100">
                                    <div class="px-4 pt-9 pb-6 d-none d-lg-block">
                                        <form class="position-relative">
                                            <input type="text" class="form-control search-chat py-2 ps-5"
                                                id="text-srh" placeholder="Search">
                                            <i
                                                class="ti ti-search position-absolute top-50 start-0 translate-middle-y fs-6 text-dark ms-3"></i>
                                        </form>
                                    </div>
                                    <div class="app-chat">
                                        <ul class="chat-users mh-n100 simplebar-scrollable-y" data-simplebar="init">
                                            <div class="simplebar-wrapper" style="margin: 0px;">
                                                <div class="simplebar-height-auto-observer-wrapper">
                                                    <div class="simplebar-height-auto-observer"></div>
                                                </div>
                                                <div class="simplebar-mask">
                                                    <div class="simplebar-offset" style="right: 0px; bottom: 0px;">
                                                        <div class="simplebar-content-wrapper" tabindex="0"
                                                            role="region" aria-label="scrollable content"
                                                            style="height: 100%; overflow: hidden scroll;">
                                                            <div class="simplebar-content" style="padding: 0px;">
                                                                <li>
                                                                    <a href="javascript:void(0)"
                                                                        class="px-4 py-3 bg-hover-light-black d-flex align-items-start justify-content-between chat-user bg-light-subtle"
                                                                        id="chat_user_1" data-user-id="1">
                                                                        <div class="position-relative w-100 ms-2">
                                                                            <div
                                                                                class="d-flex align-items-center justify-content-between mb-2">
                                                                                <h6 class="mb-0">Reza Khudhori</h6>
                                                                            </div>
                                                                            <h6 class="fw-normal text-muted">
                                                                                Tema/masalah yang ingin di bicarakan
                                                                            </h6>
                                                                            <div
                                                                                class="d-flex align-items-center justify-content-between">
                                                                                <div class="d-flex align-items-center">
                                                                                    <p class="mb-0 fs-2 text-muted">01 Juni
                                                                                        2025</p>
                                                                                </div>
                                                                                <p class="mb-0 fs-2 text-muted">05:40pm</p>
                                                                            </div>
                                                                        </div>
                                                                    </a>
                                                                </li>

                                                                <li>
                                                                    <a href="javascript:void(0)"
                                                                        class="px-4 py-3 bg-hover-light-black d-flex align-items-start justify-content-between chat-user"
                                                                        id="chat_user_2" data-user-id="2">
                                                                        <div class="position-relative w-100 ms-2">
                                                                            <div
                                                                                class="d-flex align-items-center justify-content-between mb-2">
                                                                                <h6 class="mb-0">Ibnu Haris Al Mutaqin
                                                                                </h6>
                                                                            </div>
                                                                            <h6 class="fw-normal text-muted">
                                                                                Tema/masalah yang ingin di bicarakan
                                                                            </h6>
                                                                            <div
                                                                                class="d-flex align-items-center justify-content-between">
                                                                                <div class="d-flex align-items-center">
                                                                                    <p class="mb-0 fs-2 text-muted">12 Juni
                                                                                        2025</p>
                                                                                </div>
                                                                                <p class="mb-0 fs-2 text-muted">04:00pm</p>
                                                                            </div>
                                                                        </div>
                                                                    </a>
                                                                </li>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="simplebar-placeholder" style="width: 425px; height: 904px;">
                                                </div>
                                            </div>
                                            <div class="simplebar-track simplebar-horizontal" style="visibility: hidden;">
                                                <div class="simplebar-scrollbar"
                                                    style="width: 0px; display: none; transform: translate3d(0px, 0px, 0px);">
                                                </div>
                                            </div>
                                            <div class="simplebar-track simplebar-vertical" style="visibility: visible;">
                                                <div class="simplebar-scrollbar"
                                                    style="height: 498px; transform: translate3d(0px, 0px, 0px); display: block;">
                                                </div>
                                            </div>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="w-100">
                                <div class="chat-container h-100 w-100">
                                    <div class="chat-box-inner-part h-100">
                                        <div class="chatting-box app-email-chatting-box" style="top:0">
                                            <div
                                                class="p-9 py-3 border-bottom chat-meta-user chat-active d-lg-none d-block">
                                                <ul class="list-unstyled mb-0 d-flex align-items-center">
                                                    <li class="d-lg-none d-block">
                                                        <a class="text-dark back-btn px-2 fs-5 bg-hover-primary nav-icon-hover position-relative z-index-5"
                                                            href="javascript:void(0)">
                                                            <i class="ti ti-arrow-left"></i>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="position-relative overflow-hidden">
                                                <div class="position-relative">
                                                    <div class="chat-box email-box mh-n100 p-9" data-simplebar="init">
                                                        <div class="chat-list chat active-chat" data-user-id="1">
                                                            <div
                                                                class="hstack align-items-start mb-7 pb-1 align-items-center justify-content-between flex-wrap gap-6">
                                                                <div class="d-flex align-items-center gap-2">
                                                                    <img src="../assets/images/profile/user-8.jpg"
                                                                        alt="user8" width="48" height="48"
                                                                        class="rounded-circle">
                                                                    <div>
                                                                        <h6 class="fw-semibold mb-0">
                                                                            Reza Khudhori
                                                                        </h6>
                                                                        <p class="mb-0">reza@narapatih.com</p>
                                                                    </div>
                                                                </div>
                                                                <span class="badge text-bg-primary">Progress</span>
                                                            </div>
                                                            <div class="border-bottom pb-7 mb-7">
                                                                <p class="mb-3 text-dark">
                                                                    Lorem ipsum dolor sit amet, consectetur
                                                                    adipiscing elit. Quisque bibendum
                                                                    hendrerit lobortis. Nullam ut lacus eros.
                                                                    Sed at luctus urna, eu fermentum diam. In
                                                                    et tristique mauris.
                                                                </p>
                                                                <p class="mb-3 text-dark">
                                                                    Ut id ornare metus, sed auctor enim.
                                                                    Pellentesque nisi magna, laoreet a augue
                                                                    eget, tempor volutpat diam.
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div class="chat-list chat" data-user-id="2">
                                                            <div
                                                                class="hstack align-items-start mb-7 pb-1 align-items-center justify-content-between flex-wrap gap-6">
                                                                <div class="d-flex align-items-center gap-2">
                                                                    <img src="../assets/images/profile/user-5.jpg"
                                                                        alt="user8" width="48" height="48"
                                                                        class="rounded-circle">
                                                                    <div>
                                                                        <h6 class="fw-semibold mb-0">
                                                                            Ibnu Haris Al Mutaqin
                                                                        </h6>
                                                                        <p class="mb-0">al@narapatih.com</p>
                                                                    </div>
                                                                </div>
                                                                <span class="badge text-bg-danger">Payment</span>
                                                            </div>
                                                            <div class="border-bottom pb-7 mb-7">
                                                                <p class="mb-3 text-dark">
                                                                    Lorem ipsum dolor sit amet, consectetur
                                                                    adipiscing elit. Quisque bibendum
                                                                    hendrerit lobortis. Nullam ut lacus eros.
                                                                    Sed at luctus urna, eu fermentum diam. In
                                                                    et tristique mauris.
                                                                </p>
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

                <div class="tab-pane fade" id="pills-calender" role="tabpanel" aria-labelledby="pills-calender-tab"
                    tabindex="0">
                    <div class="d-sm-flex align-items-center justify-content-between mt-3 mb-4">
                        <h3 class="mb-3 mb-sm-0 fw-semibold d-flex align-items-center">Calender
                        </h3>
                    </div>
                    <div class="row">
                    </div>
                </div>

                <div class="tab-pane fade" id="pills-members" role="tabpanel" aria-labelledby="pills-members-tab"
                    tabindex="0">
                    <div class="d-sm-flex align-items-center justify-content-between mt-3 mb-4">
                        <h3 class="mb-3 mb-sm-0 fw-semibold d-flex align-items-center">User Members <span
                                class="badge text-bg-secondary fs-2 rounded-4 py-1 px-2 ms-2">20</span>
                        </h3>
                        <form class="position-relative">
                            <input type="text" class="form-control search-chat py-2 ps-5" id="text-srh"
                                placeholder="Search members">
                            <i class="ti ti-search position-absolute top-50 start-0 translate-middle-y text-dark ms-3"></i>
                        </form>
                    </div>
                    <div class="row">
                        <div class=" col-md-6 col-xl-4">
                            <div class="card">
                                <div class="card-body p-4 d-flex align-items-center gap-6 flex-wrap">
                                    <img src="../assets/images/profile/user-1.jpg" alt="matdash-img"
                                        class="rounded-circle" width="40" height="40">
                                    <div>
                                        <h5 class="fw-semibold mb-0">Ibnu Haris Al Mutaqin</h5>
                                        <span class="fs-2 d-flex align-items-center">
                                            al.ibnuharis@gmail.com
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class=" col-md-6 col-xl-4">
                            <div class="card">
                                <div class="card-body p-4 d-flex align-items-center gap-6 flex-wrap">
                                    <img src="../assets/images/profile/user-1.jpg" alt="matdash-img"
                                        class="rounded-circle" width="40" height="40">
                                    <div>
                                        <h5 class="fw-semibold mb-0">Ibnu Haris Al Mutaqin</h5>
                                        <span class="fs-2 d-flex align-items-center">
                                            al.ibnuharis@gmail.com
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class=" col-md-6 col-xl-4">
                            <div class="card">
                                <div class="card-body p-4 d-flex align-items-center gap-6 flex-wrap">
                                    <img src="../assets/images/profile/user-1.jpg" alt="matdash-img"
                                        class="rounded-circle" width="40" height="40">
                                    <div>
                                        <h5 class="fw-semibold mb-0">Ibnu Haris Al Mutaqin</h5>
                                        <span class="fs-2 d-flex align-items-center">
                                            al.ibnuharis@gmail.com
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class=" col-md-6 col-xl-4">
                            <div class="card">
                                <div class="card-body p-4 d-flex align-items-center gap-6 flex-wrap">
                                    <img src="../assets/images/profile/user-1.jpg" alt="matdash-img"
                                        class="rounded-circle" width="40" height="40">
                                    <div>
                                        <h5 class="fw-semibold mb-0">Ibnu Haris Al Mutaqin</h5>
                                        <span class="fs-2 d-flex align-items-center">
                                            al.ibnuharis@gmail.com
                                        </span>
                                    </div>
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
