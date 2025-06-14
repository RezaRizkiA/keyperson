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
            <div class="card border-1">
                <ul class="nav nav-pills user-profile-tab" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button
                            class="nav-link position-relative rounded-0 @if (Route::currentRouteName() == 'update_profile') active @endif d-flex align-items-center justify-content-center bg-transparent fs-3 py-3"
                            id="pills-account-tab" data-bs-toggle="pill" data-bs-target="#pills-account" type="button"
                            role="tab" aria-controls="pills-account" aria-selected="true">
                            <i class="ti ti-user-circle me-2 fs-6"></i>
                            <span class="d-none d-md-block">Account</span>
                        </button>
                    </li>
                    @if (Route::currentRouteName() == 'register_expert' || $expert != null)
                        <li class="nav-item" role="presentation">
                            <button
                                class="nav-link position-relative rounded-0 @if (Route::currentRouteName() == 'register_expert') active @endif d-flex align-items-center justify-content-center bg-transparent fs-3 py-3"
                                id="pills-expert-tab" data-bs-toggle="pill" data-bs-target="#pills-expert" type="button"
                                role="tab" aria-controls="pills-expert" aria-selected="false">
                                <i class="ti ti-article me-2 fs-6"></i>
                                <span class="d-none d-md-block">Register Expert</span>
                            </button>
                        </li>
                    @endif

                    @if (Route::currentRouteName() == 'register_client' || $client != null)
                        <li class="nav-item" role="presentation">
                            <button
                                class="nav-link position-relative rounded-0 @if (Route::currentRouteName() == 'register_client') active @endif d-flex align-items-center justify-content-center bg-transparent fs-3 py-3"
                                id="pills-client-tab" data-bs-toggle="pill" data-bs-target="#pills-client" type="button"
                                role="tab" aria-controls="pills-client" aria-selected="false">
                                <i class="ti ti-article me-2 fs-6"></i>
                                <span class="d-none d-md-block">Register Client</span>
                            </button>
                        </li>
                    @endif
                </ul>
                <div class="card-body p-3 p-md-4">
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade @if (Route::currentRouteName() == 'update_profile') show active @endif" id="pills-account"
                            role="tabpanel" aria-labelledby="pills-account-tab" tabindex="0">
                            <div class="row">
                                <div class="col-lg-6 d-flex align-items-stretch">
                                    <div class="card w-100 border position-relative overflow-hidden">
                                        <div class="card-body p-4">
                                            <h4 class="card-title">Change Profile Picture</h4>
                                            <p class="card-subtitle mb-4">Change your profile picture from here</p>
                                            <form class="text-center" method="POST" action="{{ route('renew_picture') }}"
                                                enctype="multipart/form-data"> @csrf
                                                <img src="{{ urlpathSTORAGE(Auth::user()->picture) }}"
                                                    alt="{{ Auth::user()->name }}" id="imgPicture"
                                                    class="img-fluid rounded-circle" width="120" height="120">
                                                <input class="form-control" type="file" name="picture" id="inputPicture">
                                                <div class="d-flex align-items-center justify-content-center my-4 gap-6">
                                                    <button type="submit" class="btn btn-primary px-4"
                                                        id="uploadPicture">Upload</button>
                                                    <button class="btn bg-danger-subtle text-danger px-4"
                                                        id="resetPicture">Reset</button>
                                                </div>
                                                <p class="mb-0">Allowed JPG, GIF or PNG. Max size of 800K</p>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 d-flex align-items-stretch">
                                    <div class="card w-100 border position-relative overflow-hidden">
                                        <div class="card-body p-4">
                                            <h4 class="card-title">Change Password</h4>
                                            <p class="card-subtitle mb-4">To change your password please confirm here</p>
                                            @if ($errors->any())
                                                <div class="alert alert-danger">
                                                    <ul>
                                                        @foreach ($errors->all() as $error)
                                                            <li>{{ $error }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif

                                            <form action="..." method="POST">
                                                @csrf
                                                {{-- field‐field form di sini --}}
                                            </form>

                                            <form method="POST" action="{{ route('renew_password') }}"> @csrf
                                                @if (Auth::user()->password)
                                                    <div class="mb-3">
                                                        <label for="current_password" class="form-label">Current
                                                            Password</label>
                                                        <input type="password" class="form-control"
                                                            name="current_password" id="current_password">
                                                    </div>
                                                @endif

                                                <div class="mb-3">
                                                    <label for="new_password" class="form-label">New Password</label>
                                                    <input type="password" class="form-control" name="new_password"
                                                        id="new_password">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="new_password_confirmation" class="form-label">Confirm
                                                        Password</label>
                                                    <input type="password" class="form-control"
                                                        name="new_password_confirmation" id="new_password_confirmation">
                                                </div>

                                                <div class="text-center">
                                                    <button type="submit" class="btn btn-primary">Change
                                                        Password</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <h4 class="card-title">Personal Details</h4>
                                    <p class="card-subtitle mb-4">To change your personal detail , edit and save from here
                                    </p>
                                    <form action="{{ route('renew_profile') }}" method="POST"> @csrf
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="mb-4">
                                                    <label for="name" class="form-label">Your Name</label>
                                                    <input type="text" name="name" class="form-control"
                                                        id="name" placeholder="{{ Auth::user()->name }}"
                                                        value="{{ Auth::user()->name }}">
                                                </div>
                                                <div class="mb-4">
                                                    <label class="form-label" for="sex">Sex</label>
                                                    <select class="form-select" aria-label="Biologis Jenis Kelamin"
                                                        id="sex" name="gender">
                                                        <option value="L"
                                                            @if (Auth::user()->gender == 'L') selected @endif>Male</option>
                                                        <option value="P"
                                                            @if (Auth::user()->gender == 'P') selected @endif>Female
                                                        </option>
                                                    </select>
                                                </div>
                                                <div class="mb-4">
                                                    <label class="form-label">Email</label>
                                                    <input type="email" class="form-control"
                                                        placeholder="{{ Auth::user()->email }}" disabled>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-4">
                                                    <label for="slug" class="form-label">Unique URL</label>
                                                    <div class="input-group mb-3">
                                                        <span class="input-group-text">meetpro.com /</span>
                                                        <input type="text" name="slug_name" class="form-control"
                                                            id="slug" placeholder="{{ Auth::user()->slug }}"
                                                            value="{{ Auth::user()->slug }}">
                                                    </div>
                                                </div>
                                                <div class="mb-4">
                                                    <label for="phone" class="form-label">Phone</label>
                                                    <input type="text" class="form-control" name="phone"
                                                        id="phone" placeholder="{{ Auth::user()->phone }}"
                                                        value="{{ Auth::user()->phone }}">
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div>
                                                    <label for="address" class="form-label">Address</label>
                                                    <input type="text" class="form-control" id="address"
                                                        name="address" placeholder="{{ Auth::user()->address }}"
                                                        value="{{ Auth::user()->address }}">
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="d-flex align-items-center justify-content-end mt-4 gap-6">
                                                    <button type="submit" class="btn btn-primary">Save</button>
                                                    <a href="{{ route('profile') }}"
                                                        class="btn bg-danger-subtle text-danger">Cancel</a>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        @if (Route::currentRouteName() == 'register_expert' || $expert != null)

                            <div class="tab-pane fade @if (Route::currentRouteName() == 'register_expert') show active @endif"
                                id="pills-expert" role="tabpanel" aria-labelledby="pills-expert-tab" tabindex="0">
                                <form action="{{ route('register_expert_post') }}" method="POST" class="row" enctype="multipart/form-data">@csrf
                                    <div class="col-lg-8">
                                        <div class="row">
                                            <div class="col-md-9">
                                                <div class="mb-4">
                                                    <label class="form-label" for="expertise">Expertise</label>
                                                    <input name="expertise" type="text" class="form-control" id="expertise"
                                                        value="{{ old('expertise', $expert->expertise ?? '') }}">
                                                    <p class="fs-2 mb-1">Simple expertise</p>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="mb-4">
                                                    <label class="form-label" for="price">Rate/Hours</label>
                                                    <input name="price" type="number" class="form-control" id="price"
                                                        value="{{ old('price', $expert->price ?? '') }}">
                                                    <p class="fs-2 mb-1">Price / hours</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-4">
                                            <label class="form-label" for="">Expert type</label>
                                            <div class="col-auto">
                                                <div class="form-check py-0">
                                                    <input type="checkbox" value="Counselor" name="type[]" class="form-check-input" id="Counselor" aria-invalid="false" @if (in_array('Counselor', $expert->type ?? [])) checked @endif>
                                                    <label class="form-check-label font-weight-bold small" for="Counselor">Counselor</label>
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <div class="form-check py-0">
                                                    <input type="checkbox" value="Psychologist" name="type[]" class="form-check-input" id="Psychologist" aria-invalid="false" @if (in_array('Psychologist', $expert->type ?? [])) checked @endif>
                                                    <label class="form-check-label font-weight-bold small" for="Psychologist">Psychologist</label>
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <div class="form-check py-0">
                                                    <input type="checkbox" value="Coach" name="type[]" class="form-check-input" id="Coach" aria-invalid="false" @if (in_array('Coach', $expert->type ?? [])) checked @endif>
                                                    <label class="form-check-label font-weight-bold small" for="Coach">Coach</label>
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <div class="form-check py-0">
                                                    <input type="checkbox" value="Trainer" name="type[]" class="form-check-input" id="Trainer" aria-invalid="false" @if (in_array('Trainer', $expert->type ?? [])) checked @endif>
                                                    <label class="form-check-label font-weight-bold small" for="Trainer">Trainer</label>
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <div class="form-check py-0">
                                                    <input type="checkbox" value="Consultant" name="type[]" class="form-check-input" id="Consultant" aria-invalid="false" @if (in_array('Consultant', $expert->type ?? [])) checked @endif>
                                                    <label class="form-check-label font-weight-bold small" for="Consultant">Consultant</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-4">
                                            <label class="form-label">Biography</label>
                                            <div class="editor_quill" input_name="biography"
                                                data-value="{{ old('biography', $expert->biography ?? '') }}"></div>
                                            <p class="fs-2 mb-0">Set a biography to special expert.</p>
                                        </div>

                                        <div class="mb-4 repeater-group">
                                            <div class="d-flex justify-content-start align-items-center">
                                                <button type="button" data-repeater-create
                                                    class="btn bg-primary-subtle btn-sm text-primary d-flex justify-content-center py-0 px-3">
                                                    <span class="fs-4">+</span>
                                                </button>
                                                <label class="form-label m-0 ms-2">Experience</label>
                                            </div>

                                            @php $experiences = old('experiences', $expert->experiences ?? []); @endphp
                                            <div data-repeater-list="experiences">
                                                @if (count($experiences) > 0)
                                                    @foreach ($experiences as $exp)
                                                        @include('register.partial_experience_item', [
                                                            'position' => $exp['position'] ?? '',
                                                            'company' => $exp['company'] ?? '',
                                                            'years' => $exp['years'] ?? '',
                                                            'description' => $exp['description'] ?? '',
                                                        ])
                                                    @endforeach
                                                @else
                                                    @include('register.partial_experience_item', [
                                                        'position' => '',
                                                        'company' => '',
                                                        'years' => '',
                                                        'description' => '',
                                                    ])
                                                @endif
                                            </div>
                                        </div>

                                        <div class="mb-4 repeater-group">
                                            <div class="d-flex justify-content-start align-items-center">
                                                <button type="button" data-repeater-create
                                                    class="btn bg-primary-subtle btn-sm text-primary d-flex justify-content-center py-0 px-3">
                                                    <span class="fs-4">+</span>
                                                </button>
                                                <label class="form-label m-0 ms-2">Certification Licensed</label>
                                            </div>

                                            @php $licenses = old('licenses', $expert->licenses ?? []); @endphp
                                            <div data-repeater-list="licenses">
                                                @if (count($licenses) > 0)
                                                    @foreach ($licenses as $license)
                                                        @include('register.partial_license_item', [
                                                            'certification' => $license['certification'] ?? '',
                                                            'attachment' => $license['attachment'] ?? '',
                                                        ])
                                                    @endforeach
                                                @else
                                                    @include('register.partial_license_item', [
                                                        'certification' => '',
                                                        'attachment' => '',
                                                    ])
                                                @endif
                                            </div>
                                        </div>



                                        <div class="mb-4 repeater-group">
                                            <div class="d-flex justify-content-start align-items-center">
                                                <button type="button" data-repeater-create
                                                    class="btn bg-primary-subtle btn-sm text-primary d-flex justify-content-center py-0 px-3">
                                                    <span class="fs-4">+</span>
                                                </button>
                                                <label class="form-label m-0 ms-2">Gallery Photos</label>
                                            </div>

                                            @php $gallerys = old('gallerys', $expert->gallerys ?? []); @endphp
                                            <div data-repeater-list="gallerys" class="row mb-3">
                                                @if (count($gallerys) > 0)
                                                    @foreach (old('gallerys', $expert->gallerys ?? []) as $gallery)
                                                        @include('register.partial_galery_item', [
                                                            'photo' => $gallery['photos'] ?? '',
                                                        ])
                                                    @endforeach
                                                @else
                                                    @include('register.partial_galery_item', [
                                                        'photo' => '',
                                                    ])
                                                @endif
                                            </div>
                                        </div>

                                        <div class="mb-4 repeater-group">
                                            <div class="d-flex justify-content-start align-items-center">
                                                <button type="button" data-repeater-create
                                                    class="btn bg-primary-subtle btn-sm text-primary d-flex justify-content-center py-0 px-3">
                                                    <span class="fs-4">+</span>
                                                </button>
                                                <label class="form-label m-0 ms-2">Social Media</label>
                                            </div>

                                            @php $socials = old('socials', $expert->socials ?? []); @endphp
                                            <div data-repeater-list="socials" class="row mb-1">
                                                @if (count($socials) > 0)
                                                    @foreach ($socials as $item)
                                                        @include('register.partial_social_item', [
                                                            'key' => is_array($item)
                                                                ? $item['key'] ?? ($item['0'] ?? '')
                                                                : '',
                                                            'value' => is_array($item)
                                                                ? $item['value'] ?? ($item['1'] ?? '')
                                                                : '',
                                                        ])
                                                    @endforeach
                                                @else
                                                    @include('register.partial_social_item')
                                                @endif
                                            </div>
                                            <p class="fs-2 mb-0">Set a your social media</p>
                                        </div>
                                    </div>

                                    <div class="col-lg-4">
                                        <div class="mb-4">
                                            <label class="form-label">Choose Your Specialist Expert</label>
                                            @include('register.parital_expertise', [
                                                'expertises' => $expertises,
                                                'expertiseSelected' => $expert?->expertise_id ?? []
                                            ])
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="d-flex align-items-center justify-content-end mt-4 gap-6">
                                            <button class="btn btn-primary">Save</button>
                                            <button class="btn bg-danger-subtle text-danger">Cancel</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        @endif

                        @if (Route::currentRouteName() == 'register_client' || $client != null)
                            <div class="tab-pane fade @if (Route::currentRouteName() == 'register_client') show active @endif"
                                id="pills-client" role="tabpanel" aria-labelledby="pills-client-tab"tabindex="0">
                                <form action="{{ route('register_client_post') }}" method="POST" class="row"
                                    enctype="multipart/form-data">@csrf
                                    <div class="col-lg-8">
                                        <div class="mb-4">
                                            <label class="form-label">Section Hero</label>
                                            <textarea class="form-control" placeholder="Section Hero" rows="2" name="section_hero">{{ old('section_hero', $client->section_hero ?? '') }}</textarea>
                                            <p class="fs-2 mb-0">Set a section hero / tagline</p>
                                        </div>

                                        <div class="mb-4">
                                            <label class="form-label">Section Banner</label>
                                            <input type="text" class="form-control" placeholder="Banner Title"
                                                name="banner_title"
                                                value="{{ old('banner_title', $client->banner_title ?? '') }}" />
                                            <p class="fs-2 mb-2">Set a banner title / message</p>

                                            <textarea class="form-control" placeholder="Banner Description" rows="5" name="banner_desc">{{ old('banner_desc', $client->banner_desc ?? '') }}</textarea>
                                            <p class="fs-2 mb-2">Set a banner detail message</p>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <input type="text" class="form-control"
                                                        placeholder="Banner Author Name" name="author_name"
                                                        value="{{ old('author_name', $client->author_name ?? '') }}" />
                                                    <p class="fs-2 mb-2">Set a banner author name</p>
                                                </div>
                                                <div class="col-md-6">
                                                    <input class="form-control" type="file" name="file_author_photo">
                                                    <p class="fs-2 mb-2">
                                                        @if ($client == null || $client->author_photo == null)
                                                            Set author profile
                                                        @else
                                                            <a target="_blank" class="text-muted"
                                                                href="{{ urlpathSTORAGE($client->author_photo) }}">Old
                                                                Profile: {{ basename($client->author_photo) }}</a>
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>
                                            <input class="form-control" type="file" name="file_banner_background">
                                            <p class="fs-2 mb-2">
                                                @if ($client == null || $client->author_photo == null)
                                                    Set a background banner
                                                @else
                                                    <a target="_blank" class="text-muted"
                                                        href="{{ urlpathSTORAGE($client->banner_background) }}">Old
                                                        Background: {{ basename($client->banner_background) }}</a>
                                                @endif
                                            </p>
                                        </div>

                                        <div class="mb-4">
                                            <label class="form-label">Section Footer</label>
                                            <input type="text" class="form-control" placeholder="Footer Title"
                                                name="banner_footer"
                                                value="{{ old('banner_footer', $client->banner_footer ?? '') }}" />
                                            <p class="fs-2 mb-2">Set a footer title</p>

                                            <textarea class="form-control" placeholder="Footer Description" rows="4" name="banner_footer_desc">{{ old('banner_footer_desc', $client->banner_footer_desc ?? '') }}</textarea>
                                            <p class="fs-2 mb-2">Set a footer message</p>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <input type="text" id="hue-demo" class="form-control demo"
                                                        data-control="hue"
                                                        value="{{ old('color', $client->color ?? '') }}"
                                                        name="color" />
                                                    <p class="fs-2 mb-2">Set a footer color</p>
                                                </div>
                                                <div class="col-md-6">
                                                    <input class="form-control" type="file" name="file_logo">
                                                    <p class="fs-2 mb-2">Set logo
                                                        @if ($client == null || $client->author_photo == null)
                                                            Set logo
                                                        @else
                                                            <a target="_blank" class="text-muted"
                                                                href="{{ urlpathSTORAGE($client->logo) }}">Old
                                                                Logo: {{ basename($client->logo) }}</a>
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-4">
                                            <label class="form-label">Section Page</label>
                                            <div class="input-group mb-3">
                                                <span class="input-group-text">meetpro.com /</span>
                                                <input type="text" class="form-control" id="slug"
                                                    name="slug_page"
                                                    value="{{ old('slug_page', $client->slug_page ?? '') }}">
                                            </div>
                                        </div>

                                    </div>

                                    <div class="col-lg-4">
                                        <div class="mb-4">
                                            <label class="form-label">Choose specialist you are showing</label>
                                            @include('register.parital_expertise', [
                                                'expertises' => $expertises,
                                                'expertiseSelected' => $client?->expertise_id ?? []
                                            ])
                                            {{-- <div class="card border position-relative overflow-hidden mb-0">
                                                <div class="card-body p-2" style="max-height: 900px; overflow-y: auto;">
                                                    @foreach ($expertise_categories as $category)
                                                        <div class="mb-3 form-group validate">
                                                            <label class="form-label">{{ $category->name }} <span
                                                                    class="text-danger">*</span></label>
                                                            <div class="controls">
                                                                @foreach ($category->expertises as $expertise)
                                                                    <fieldset>
                                                                        <div class="form-check py-0">
                                                                            <input type="checkbox"
                                                                                value="{{ $expertise->id }}"
                                                                                name="expertise_id[]"
                                                                                class="form-check-input"
                                                                                id="expert{{ $expertise->id }}"
                                                                                aria-invalid="false"
                                                                                @if ($client != null && in_array($expertise->id, $client->expertise_id)) checked @endif>
                                                                            <label class="form-check-label"
                                                                                for="expert{{ $expertise->id }}">{{ $expertise->name }}</label>
                                                                        </div>
                                                                    </fieldset>
                                                                @endforeach
                                                                <div class="help-block"></div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div> --}}
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="d-flex align-items-center justify-content-end mt-4 gap-6">
                                            <button type="submit" class="btn btn-primary">Save</button>
                                            <a href="{{ route('profile') }}"
                                                class="btn bg-danger-subtle text-danger">Cancel</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Mendapatkan elemen-elemen dari DOM
            const uploadButton = document.getElementById('uploadPicture');
            const resetButton = document.getElementById('resetPicture');
            const fileInput = document.getElementById('inputPicture');
            const imgPreview = document.getElementById('imgPicture');

            // Disable tombol upload saat pertama kali halaman dimuat
            uploadButton.disabled = true;

            // Sembunyikan input file dan reset tombol saat pertama kali dimuat
            fileInput.style.display = 'none'; // Sembunyikan input file

            // Reset gambar dan menampilkan input file saat tombol Reset diklik
            resetButton.addEventListener('click', function() {
                // Hapus gambar preview
                imgPreview.src = '';

                // Tampilkan input file untuk memilih gambar baru
                fileInput.style.display = 'block'; // Menampilkan input file
                imgPreview.style.display = 'none'; // Menampilkan input file
                resetButton.disabled = true; // Disable tombol reset setelah di klik
                uploadButton.disabled = false; // Enable tombol upload
            });

            // Ketika pengguna memilih gambar baru
            fileInput.addEventListener('change', function() {
                const file = fileInput.files[0]; // Ambil file yang dipilih

                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        // Perbarui gambar preview dengan gambar baru
                        imgPreview.src = e.target.result;
                    };
                    reader.readAsDataURL(file); // Baca file sebagai data URL
                }
            });
        });
    </script>
@endsection

@section('footer')
    @include('partials.footer')
@endsection
