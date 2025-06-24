@extends('layout')

@section('top')
    <section class="py-4 py-md-5 bg-light-gray">
        <div class="container-fluid">
            <div class="d-flex justify-content-between flex-md-nowrap flex-wrap">
                <h2 class="fs-13 fw-bolder ">
                    Create Appointment
                </h2>
                <div class="d-flex align-items-center gap-6">
                    <a href="{{ route('home') }}" class="text-muted fw-bolder link-primary fs-3 text-uppercase">
                        Home
                    </a>
                    <iconify-icon icon="solar:alt-arrow-right-outline" class="fs-5 text-muted"></iconify-icon>
                    <a href="#" class="text-primary link-primary fw-bolder fs-3 text-uppercase">
                        Appointment
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('content')
    <section class="pt-5 pt-md-14 pb-4 pb-md-5">
        <div class="container-fluid">
            <div class="card border">
                <div class="card-body p-4">
                    <section>
                        <form action="{{ route('appointment_post', $expert->id) }}" method="POST">@csrf
                            <div class="table-responsive">
                                <table class="table align-middle text-nowrap mb-0">
                                    <thead class="fs-2">
                                        <tr>
                                            <th>Expert</th>
                                            <th>Hours</th>
                                            <th class="text-end">Price</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="border-bottom-0">
                                                <div class="d-flex align-items-center gap-3 overflow-hidden">
                                                    <img src="{{ urlpathSTORAGE($expert->user->picture) }}"
                                                        alt="matdash-img" class="img-fluid rounded" width="80">
                                                    <div>
                                                        <h6 class="fw-semibold fs-4 mb-0">{{ $expert->user->name }}</h6>
                                                        <p class="mb-0">{{ $expert->expertise }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="border-bottom-0">
                                                <div class="input-group input-group-sm flex-nowrap rounded">
                                                    <button
                                                        class="btn minus min-width-40 py-0 border-end border-muted border-end-0 text-muted"
                                                        type="button" id="add1">
                                                        <i class="ti ti-minus"></i>
                                                    </button>
                                                    <input name="hours" type="text"
                                                        class="min-width-40 flex-grow-0 border border-muted text-muted fs-3 fw-semibold form-control text-center qty"
                                                        placeholder="" aria-label="Example text with button addon"
                                                        aria-describedby="add1" value="1" id="qty"
                                                        data-target-id='priceDisplay'>
                                                    <button
                                                        class="btn min-width-40 py-0 border border-muted border-start-0 text-muted add"
                                                        type="button" id="addo2">
                                                        <i class="ti ti-plus"></i>
                                                    </button>
                                                </div>
                                            </td>
                                            <td class="text-end border-bottom-0">
                                                <h6 class="fs-4 fw-semibold mb-0" data-price="100000" id="priceDisplay">Rp.
                                                    100.000</h6>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="order-summary border rounded p-3 p-md-4 my-4">
                                <h5 class="fs-5 fw-semibold mb-4">Appointment</h5>
                                <div class="mb-4">
                                    <label class="form-label">Detail Appointment</label>
                                    <textarea name="appoinment" class="form-control" placeholder="" rows="2"></textarea>
                                    <p class="fs-2 mb-2">Input detail appointment</p>
                                </div>

                                <div class="mb-0">
                                    <label class="form-label">Date Time</label>
                                    <div class="d-flex align-items-center gap-6 py-1 flex-nowrap">
                                        <input name="date" type="text" class="form-control" placeholder="2023-06-04"
                                            id="mdate" />
                                        <input name="time" class="form-control" id="timepicker"
                                            placeholder="Check time" />
                                    </div>
                                    <p class="fs-2 mb-0">Request date and time</p>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </form>
                    </section>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('footer')
    @include('partials.footer')
@endsection

@section('script')
@endsection
