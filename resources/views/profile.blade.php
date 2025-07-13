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
                                            class="ti ti-file-description fs-6 d-block me-2"></i>{{ $appointmentsCount }}</h4>
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
                                            <img src="{{ urlpathSTORAGE(Auth::user()->picture) ?? asset('assets/images/profile/user-3.jpg') }}"
                                                alt="profile-img" class="w-100 h-100 object-fit-cover rounded-circle">
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <h5 class="mb-0">{{ Auth::user()->name }}</h5>
                                    <p class="mb-0">{{ Auth::user()->email }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 order-last">
                            <ul
                                class="list-unstyled d-flex align-items-center justify-content-center justify-content-lg-end my-3 mx-4 gap-3">
                                {{-- button sosmed expert --}}
                                @if (in_array('expert', Auth::user()->roles ?? []))
                                    @php
                                        $socialConfig = [
                                            'facebook' => [
                                                'class' => 'btn-primary',
                                                'icon' => 'ti ti-brand-facebook',
                                            ],
                                            'instagram' => [
                                                'class' => 'btn-secondary',
                                                'icon' => 'ti ti-brand-instagram',
                                            ],
                                            'youtube' => [
                                                'class' => 'btn-danger',
                                                'icon' => 'ti ti-brand-youtube',
                                            ],
                                            'linkedin' => [
                                                'class' => 'btn-info',
                                                'icon' => 'ti ti-brand-linkedin',
                                            ],
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
                                {{-- button mypage client --}}
                                @if (in_array('client', Auth::user()->roles ?? []))
                                    <li class="">
                                        <a href="{{ route('home_client', Auth::user()->client->slug_page) }}"
                                            class="btn btn-outline-primary border-2 text-nowrap py-2">My Page</a>
                                    </li>
                                @endif

                            </ul>
                        </div>
                    </div>
                    <ul class="nav nav-pills user-profile-tab justify-content-end mt-2 bg-primary-subtle rounded-2 rounded-top-0"
                        id="pills-tab" role="tablist">
                        @if (in_array('administrator', Auth::user()->roles ?? []))
                            <li class="nav-item" role="presentation">
                                <button class="nav-link hstack gap-2 rounded-0 fs-12 py-6" id="pills-expertise-tab"
                                    data-bs-toggle="pill" data-bs-target="#pills-expertise" type="button" role="tab"
                                    aria-controls="pills-expertise" aria-selected="true">
                                    <i class="ti ti-settings fs-5"></i>
                                    <span class="d-none d-md-block">Expertises</span>
                                </button>
                            </li>
                        @endif

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
                            <button class="nav-link hstack gap-2 rounded-0 fs-12 py-6" id="pills-calendar-tab"
                                data-bs-toggle="pill" data-bs-target="#pills-calendar" type="button" role="tab"
                                aria-controls="pills-calendar" aria-selected="false">
                                <i class="ti ti-photo fs-5"></i>
                                <span class="d-none d-md-block">Calendar</span>
                            </button>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="tab-content" id="pills-tabContent">
                @if (in_array('administrator', Auth::user()->roles ?? []))
                    <div class="modal fade" id="expertise-modal">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header align-items-start">
                                    <div class="">
                                        <p class="small mb-0">Skill Under</p>
                                        <h5 class="modal-title" id="parent_name">
                                            Business Management
                                        </h5>
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <form method="POST" enctype="multipart/form-data"> @csrf
                                    <div class="modal-body">
                                        <div class="mb-4">
                                            <label for="name" class="mb-1">Skill Name:</label>
                                            <input type="text" name="name" class="form-control" id="name"
                                                required>
                                        </div>
                                        <div class="mb-4">
                                            <label for="name" class="mb-1">Scope Skill:</label>
                                            <div class="input-group">
                                                <label class="input-group-text" for="parent_id">Under</label>
                                                <select name="parent_id" class="form-select" id="parent_id" required>
                                                    <option value="">Core Skill</option>
                                                    @foreach ($expertises as $expertise)
                                                        <option value="{{ $expertise->id }}">{{ $expertise->name }}
                                                        </option>
                                                        @foreach ($expertise->childrensRecursive as $expertise)
                                                            <option value="{{ $expertise->id }}">{{ $expertise->name }}
                                                            </option>
                                                        @endforeach
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="mb-4">
                                            <label for="file_ilustration_img mb-1" class="mb-1">Ilustration Img:</label>
                                            <input name="file_ilustration_img" class="form-control" type="file"
                                                id="file_ilustration_img">
                                            <i class="fs-2"><b>File IMG</b> <a target="_blank" class="fw-bold"
                                                    href="" id="ilustration_img"></a></i>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn bg-danger-subtle text-danger "
                                            data-bs-dismiss="modal">
                                            Close.
                                        </button>
                                        <button type="submit" class="btn btn-success">
                                            Submit Form
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="pills-expertise" role="tabpanel"
                        aria-labelledby="pills-expertise-tab" tabindex="0">
                        <div class="card card-body border">
                            <div class="d-sm-flex align-items-center justify-space-between">
                                <div class="ms-auto">
                                    <a type="button" data-bs-toggle="modal" data-bs-target="#expertise-modal"
                                        data-action="{{ route('store_expertise') }}" data-parent_name="Core Skill"
                                        data-parent_id="" class="badge fw-bolder py-2 fs-2 bg-danger-subtle text-danger">
                                        Create Core Skill
                                    </a>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table search-table align-middle text-nowrap">
                                    <thead class="header-item">
                                        <th>Expertise Name</th>
                                        <th class="text-center">Experts</th>
                                        <th>Action</th>
                                    </thead>
                                    <tbody>
                                        @foreach ($expertises as $expertise)
                                            <tr class="search-items">
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        @php $color = $expertise->level == 1 ? 'primary' : ($expertise->level == 2 ? 'warning' : ($expertise->level == 3 ? 'success' : 'secondary')); @endphp
                                                        <span
                                                            class="badge bg-{{ $color }}-subtle text-{{ $color }} fw-bolder">Lvl.{{ $expertise->level }}</span>
                                                        <div class="ms-3">
                                                            <h6 class="user-name mb-0 text-wrap" data-name="Emma Adams">
                                                                {{ $expertise->name }}</h6>
                                                            <span class="user-work fs-3"
                                                                data-occupation="Web Developer">{{ $expertise->parent ? "Skill under {$expertise->parent->name}" : 'Core Skill' }}</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <h5 class="fs-4 fw-semibold mb-0">1</h5>
                                                </td>
                                                <td>
                                                    <div class="action-btn d-flex align-items-center">
                                                        <a type="button" data-bs-toggle="modal"
                                                            data-bs-target="#expertise-modal"
                                                            data-action="{{ route('update_expertise', $expertise->id) }}"
                                                            data-expertise="{{ json_encode(['name' => $expertise->name, 'ilustration_img' => $expertise->ilustration_img]) }}"
                                                            data-parent_name="{{ $expertise->parent->name ?? 'Core Skill' }}"
                                                            data-parent_id="{{ $expertise->parent_id }}"
                                                            class="text-primary edit">
                                                            <i class="ti ti-edit fs-5"></i>
                                                        </a>
                                                        <a href="{{ route('destroy_expertise', $expertise->id) }}"
                                                            class="text-dark delete ms-2">
                                                            <i class="ti ti-trash fs-5"></i>
                                                        </a>

                                                        @if ($expertise->level != 3)
                                                            <a type="button" data-bs-toggle="modal"
                                                                data-bs-target="#expertise-modal"
                                                                data-action="{{ route('store_expertise') }}"
                                                                data-parent_name="{{ $expertise->name }}"
                                                                data-parent_id="{{ $expertise->id }}"
                                                                class="badge fw-bold fs-2 bg-{{ $color }}-subtle text-{{ $color }} ms-2">
                                                                Add Skill
                                                            </a>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="tab-pane fade show active" id="pills-appointment" role="tabpanel"
                    aria-labelledby="pills-appointment-tab" tabindex="0">
                    <div class="card overflow-hidden chat-application border">
                        <div class="d-flex w-100">
                            <div class="min-width-200">
                                <div class="border-end user-chat-box h-100">
                                    <div class="px-4 pt-9 pb-6 d-none d-lg-block">
                                        <form class="position-relative">
                                            <input type="text" class="form-control search-chat py-2 ps-5"
                                                id="text-srh" placeholder="Cari Nama Konselor...">
                                            <i class="ti ti-search position-absolute top-50 start-0 translate-middle-y fs-6 text-dark ms-3"></i>
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
                                                                @forelse ($appointments as $idx => $appointment)
                                                                    @include(
                                                                        'components.appointment.item',
                                                                        [
                                                                            'appointment' => $appointment,
                                                                            'index' => $idx,
                                                                            'active' => $loop->first,
                                                                            'isExpert' => $isExpert,
                                                                        ]
                                                                    )
                                                                @empty
                                                                    <li class="px-4 py-4 text-center text-muted">Tidak ada
                                                                        appointment.</li>
                                                                @endforelse

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
                                                        @foreach ($appointments as $idx => $appointment)
                                                            @include('components.appointment.detail', [
                                                                'appointment' => $appointment,
                                                                'index' => $idx,
                                                                'active' => $loop->first,
                                                                'isExpert' => $isExpert,
                                                            ])
                                                        @endforeach

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
                    </div>
                </div>

                <div class="tab-pane fade" id="pills-calendar" role="tabpanel" aria-labelledby="pills-calendar-tab"
                    tabindex="0">
                    <div class="card">
                        <div class="card-body calendar-sidebar app-calendar">
                            <div id="calendar">
                                <script>
                                    window.calendarAppointmentsData = @json($calendarAppointments);
                                </script>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-scrollable modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="eventModalLabel">
                                        Event Details
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-12" id="event-title-group">
                                            <div>
                                                <label class="form-label">Event Title</label>
                                                <input id="event-title" type="text" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="col-md-12" id="event-title-display-group" style="display: none;">
                                            <div>
                                                <label class="form-label">Event Title</label>
                                                <p id="event-title-display" class="form-control-static"></p>
                                            </div>
                                        </div>
                                        <div class="col-md-12 mt-6" id="event-color-group">
                                            <div>
                                                <label class="form-label">Event Color</label>
                                            </div>
                                            <div class="d-flex">
                                                <div class="n-chk">
                                                    <div class="form-check form-check-primary form-check-inline">
                                                        <input class="form-check-input" type="radio" name="event-level"
                                                            value="Danger" id="modalDanger" />
                                                        <label class="form-check-label" for="modalDanger">Danger</label>
                                                    </div>
                                                </div>
                                                <div class="n-chk">
                                                    <div class="form-check form-check-warning form-check-inline">
                                                        <input class="form-check-input" type="radio" name="event-level"
                                                            value="Success" id="modalSuccess" />
                                                        <label class="form-check-label" for="modalSuccess">Success</label>
                                                    </div>
                                                </div>
                                                <div class="n-chk">
                                                    <div class="form-check form-check-success form-check-inline">
                                                        <input class="form-check-input" type="radio" name="event-level"
                                                            value="Primary" id="modalPrimary" />
                                                        <label class="form-check-label" for="modalPrimary">Primary</label>
                                                    </div>
                                                </div>
                                                <div class="n-chk">
                                                    <div class="form-check form-check-danger form-check-inline">
                                                        <input class="form-check-input" type="radio" name="event-level"
                                                            value="Warning" id="modalWarning" />
                                                        <label class="form-check-label" for="modalWarning">Warning</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12 mt-6" id="event-start-date-group">
                                            <div>
                                                <label class="form-label">Enter Start Date</label>
                                                <input id="event-start-date" type="date" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="col-md-12 mt-6" id="event-start-date-display-group"
                                            style="display: none;">
                                            <div>
                                                <label class="form-label">Start Date</label>
                                                <p id="event-start-date-display" class="form-control-static"></p>
                                            </div>
                                        </div>

                                        <div class="col-md-12 mt-6" id="event-end-date-group">
                                            <div>
                                                <label class="form-label">End Date</label>
                                                <p id="event-end-date-display" class="form-control-static"></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn bg-danger-subtle text-danger"
                                        data-bs-dismiss="modal">
                                        Close
                                    </button>
                                    <button type="button" class="btn btn-success btn-update-event"
                                        data-fc-event-public-id="">
                                        Update changes
                                    </button>
                                    <button type="button" class="btn btn-primary btn-add-event">
                                        Add Event
                                    </button>
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

@section('script')
    <script>
        const modal = document.getElementById('expertise-modal');

        modal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;

            // Ambil semua data dari tombol
            const action = button.getAttribute('data-action');
            const parentName = button.getAttribute('data-parent_name') || 'Core Skill';
            const parentId = button.getAttribute('data-parent_id');
            const expertiseData = button.getAttribute('data-expertise');

            // Ambil form dan reset terlebih dahulu
            const form = modal.querySelector('form');
            form.reset();

            // Set action form
            form.setAttribute('action', action);

            // Set nama parent di judul modal
            modal.querySelector('#parent_name').textContent = parentName;

            // Atur selected parent_id di dropdown
            const select = modal.querySelector('#parent_id');
            console.log(parentId);
            select.value = parentId ?? ''; // fallback ke kosong kalau NULL

            // Atur input dan link jika mode edit
            if (expertiseData) {
                try {
                    const data = JSON.parse(expertiseData);
                    modal.querySelector('input[name="name"]').value = data.name || '';
                    const ilustrationLink = modal.querySelector('#ilustration_img');
                    if (data.ilustration_img) {
                        const {
                            endpoint,
                            bucket
                        } = window.S3_CONFIG;
                        const fullURL = `${endpoint}/${bucket}/${data.ilustration_img.replace(/^\/+/, '')}`;
                        ilustrationLink.href = fullURL;
                        ilustrationLink.textContent = data.ilustration_img;
                    } else {
                        ilustrationLink.href = '';
                        ilustrationLink.textContent = '';
                    }

                } catch (e) {
                    console.error('Invalid JSON in data-expertise:', e);
                }
            } else {
                // Kosongkan input & link jika bukan edit
                modal.querySelector('input[name="name"]').value = '';
                const ilustrationLink = modal.querySelector('#ilustration_img');
                ilustrationLink.href = '';
                ilustrationLink.textContent = '';
            }
        });
    </script>
    <script src="{{ asset('assets/libs/fullcalendar/index.global.min.js') }}"></script>
    <script src="{{ asset('assets/js/apps/calendar-init.js') }}"></script>
@endsection
