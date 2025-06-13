@extends('layout')

@section('top')
    <section class="py-5 bg-light-gray">
        <div class="container-fluid">
            <div class="d-flex justify-content-between flex-md-nowrap flex-wrap">
                <h2 class="fs-15 fw-bolder ">
                    Explore Profesional Expert
                </h2>
                <div class="d-flex align-items-center gap-6">
                    <a href="{{ route('home_client', $client->slug_page) }}" class="text-muted fw-bolder link-primary fs-3 text-uppercase">
                        Home
                    </a>
                    <iconify-icon icon="solar:alt-arrow-right-outline" class="fs-5 text-muted"></iconify-icon>
                    <a href="#" class="text-primary link-primary fw-bolder fs-3 text-uppercase">
                        Expert
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('content')
    <section class="pt-5 pt-md-14 pb-4 pb-md-5">
        <div class="container-fluid">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h3 class="mb-3 mb-sm-0 fw-semibold d-flex align-items-center">Expert <span
                        class="badge text-bg-secondary fs-2 rounded-4 py-1 px-2 ms-2">{{ $experts->count() }}</span>
                </h3>
                <form class="position-relative">
                    <input type="text" class="form-control search-chat py-2 ps-5" id="text-srh"
                        placeholder="Search Expert">
                    <i class="ti ti-search position-absolute top-50 start-0 translate-middle-y text-dark ms-3"></i>
                </form>
            </div>

            <div class="row">
                @foreach ($experts as $expert)
                    <div class="col-md-3">
                        <a href="{{ route('expert_detail', ['expert_id' => $expert->id]) }}?back={{ urlencode(request()->fullUrl()) }}" class="card overflow-hidden">
                            <div class="position-relative">
                                <img src="{{ urlpathSTORAGE($expert->user->picture) }}" alt="leader" class="w-100">
                                <div
                                    class="position-absolute start-50 translate-middle-x bottom-5 bg-white rounded shadow py-3 px-2 text-center w-90">
                                    <h4 class="fs-5 fw-bold mb-8">{{ $expert->user->name }}</h4>
                                    <p class="fs-3 mb-0">{{ $expert->expertise }}</p>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection

@section('footer')
    @include('partials.footer')
@endsection
