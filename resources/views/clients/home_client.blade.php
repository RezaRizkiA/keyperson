@extends('layout')

@section('header')
    @include('partials.header')
@endsection

@section('top')
    <section class="py-5 bg-light-gray">
        <div class="container-fluid">
            <div class="d-flex justify-content-between flex-md-nowrap flex-wrap">
                <h2 class="fs-15 fw-bolder ">
                    {{ $client->section_hero }}
                </h2>
                <div class="d-flex align-items-center gap-6">
                    <a href="{{ route('home') }}" class="text-muted fw-bolder link-primary fs-3 text-uppercase">
                        MeetPro
                    </a>
                    <iconify-icon icon="solar:alt-arrow-right-outline" class="fs-5 text-muted"></iconify-icon>
                    <a href="#" class="text-primary link-primary fw-bolder fs-3 text-uppercase">
                        Client
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
                        <div class="p-4 p-lg-5 flex-grow-1">
                            <div class="py-lg-4 d-flex flex-column gap-3">
                                <a href="{{ route('expert') }}">
                                    <h2 class="fw-bolder fs-8">
                                        {{ $client->banner_title }}
                                    </h2>
                                </a>
                                <p class="">
                                    {{ $client->banner_desc }}
                                </p>
                                <div class="d-flex justify-content-end align-items-center">
                                    <div class="d-flex gap-2">
                                        <i class="ti ti-circle"></i>
                                        <p class="mb-0 fs-1 fw-semibold text-dark">{{ $client->author_name }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 order-first order-lg-last">
                        <div class="blog-bg d-flex flex-column justify-content-between p-9 h-100 flex-grow-1"
                            style="background-image: url('{{ urlpathSTORAGE($client->banner_background) }}')">
                            <img src="{{ urlpathSTORAGE($client->author_photo) }}" alt="user" width="44"
                                height="44" class="rounded-circle">
                            <div class="d-flex justify-content-end">
                                <p class="fs-2 py-1 px-2 bg-white rounded-1 fw-semibold mb-0 text-dark">29 profesional
                                    expert
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">

                @foreach ($categories as $category)
                    <div class="col-lg-4 col-md-6">
                        <a href="{{ route('list_conselor', [$client->slug_page, $category->slug]) }}"
                            class="card rounded-3 overflow-hidden">
                            <div class="position-relative">
                                <img src="{{ asset('assets/images/frontend-pages/blog-1.jpg') }}" alt="blog image"
                                    class="w-100 img-fluid">
                                <div class="position-absolute bottom-0 ms-7 mb-n9">
                                    <img src="{{ asset('assets/images/profile/user-3.jpg') }}" alt="user"
                                        class="rounded-circle" width="44px" height="44px">
                                </div>
                            </div>
                            <div class="mt-7 px-7 pb-7 h-100">
                                <div class="d-flex gap-3 flex-column h-100 justify-content-between">
                                    <div class="d-flex">
                                        <p class="fs-3 px-2 rounded-pill bg-muted bg-opacity-25 text-dark mb-0">
                                            {{ $category->name }}
                                        </p>
                                    </div>
                                    <p class="fs-5 fw-bolder text-truncate">
                                        {{ $category->desc }}
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex gap-9">
                                            <div class="d-flex gap-1">
                                                <i class="ti ti-message fs-5 text-dark"></i>
                                                <p class="mb-0 fs-2 fw-bold">{{ $category->selected_expertise_count }} Expertises</p>
                                            </div>
                                            <div class="d-flex gap-1">
                                                <i class="ti ti-users fs-5 text-dark"></i>
                                                <p class="mb-0 fs-2 fw-bold">{{ $category->total_expert_in_category }} Experts</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="py-lg-11 py-5 position-relative" style="background: {{ $client->color }}">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-910 text-center">
                    <a class="round-56 mx-auto rounded-2 shadow bg-white hstack justify-content-center" href="">
                        <img src="{{ urlpathSTORAGE($client->logo) }}" alt="logo">
                    </a>
                    <h2 class="fs-15 my-9 fw-bolder text-white text-center lh-sm">
                        {{ $client->banner_footer }}
                    </h2>
                    <p class="text-white fs-5 mb-9 px-xl-11">
                        {{ $client->banner_footer_desc }}
                    </p>
                    @if (!Auth::check())
                        <a href="{{ route('google_redirect') }}" class="btn btn-outline-light">
                            Register
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection

@section('footer')
    @include('partials.footer')
@endsection
