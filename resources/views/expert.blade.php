@extends('layout')

@section('top')
{{-- <section class="py-5 bg-light-gray">
    <div class="container-fluid">
      <div class="d-flex justify-content-between flex-md-nowrap flex-wrap">
        <h2 class="fs-15 fw-bolder ">
          Meet our Expert
        </h2>
        <div class="d-flex align-items-center gap-6">
          <a href="" class="text-muted fw-bolder link-primary fs-3 text-uppercase">
            MeetPro
          </a>
          <iconify-icon icon="solar:alt-arrow-right-outline" class="fs-5 text-muted"></iconify-icon>
          <a href="#" class="text-primary link-primary fw-bolder fs-3 text-uppercase">
            List Expert
          </a>
        </div>
      </div>
    </div>
  </section> --}}
@endsection

@section('content')
<section class="py-7 py-md-14 py-lg-11 bg-light-gray">
  <div class="container-fluid">
    <div class="row justify-content-between mb-5">
      <div class="col-lg-7 mb-5 mb-lg-0">
        <h4 class="fs-10 fw-bolder">
          Langkah untuk Terhubung dengan Para Expert
        </h4>
      </div>
    </div>

    <ul class="nav nav-pills tabs-pills justify-content-between gap-3" id="pills-tab" role="tablist">
      <li class="nav-item flex-grow-1" role="presentation">
        <button class="nav-link active fs-4 fw-semibold px-4 py-6 tabs-shadow" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">
          <iconify-icon icon="material-symbols:groups-outline-rounded" class="fs-7 "></iconify-icon>
          Temukan Expert
        </button>
      </li>
      <li class="nav-item flex-grow-1" role="presentation">
        <button class="nav-link fs-4 fw-semibold px-4 py-6 tabs-shadow" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">
          <iconify-icon icon="material-symbols:account-balance-outline" class="fs-7"></iconify-icon>
          Payments
        </button>
      </li>
      <li class="nav-item flex-grow-1" role="presentation">
        <button class="nav-link fs-4 fw-semibold px-4 py-6 tabs-shadow" id="pills-contact-tab" data-bs-toggle="pill" data-bs-target="#pills-contact" type="button" role="tab" aria-controls="pills-contact" aria-selected="false">
          <iconify-icon icon="material-symbols-light:photo-frame-outline-sharp" class="fs-7"></iconify-icon>
          Konfirmasi Jadwal
        </button>
      </li>
      <li class="nav-item flex-grow-1" role="presentation">
        <button class="nav-link fs-4 fw-semibold px-4 py-6 tabs-shadow" id="pills-disabled-tab" data-bs-toggle="pill" data-bs-target="#pills-disabled" type="button" role="tab" aria-controls="pills-disabled" aria-selected="false">
          <iconify-icon icon="material-symbols:widgets-outline-rounded" class="fs-7"></iconify-icon>
          Mulai Sesi
        </button>
      </li>
    </ul>
    <div class="tab-content mt-7 mt-lg-12 pb-lg-9" id="myTabContent">
      <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="team-tab" tabindex="0">
        <div class="row gap-lg-0 gap-7">
          <div class="col-lg-6">
            <div class="bg-primary-subtle rounded-24 p-13">
              <img src="{{asset('assets/images/frontend-pages/tabsimage.png')}}" alt="icon" class="w-100">
            </div>
          </div>
          <div class="col-lg-6">
            <div class="d-flex flex-column h-100 justify-content-center align-items-start ps-lg-7 ms-lg-8">
              <h2 class="fs-10 fw-bolder mb-0">Temukan Expert</h2>
              <div class="accordion tabs-accordion my-4 w-100" id="accordionExample1">
                <div class="accordion-item border-0 border-bottom">
                  <h2 class="accordion-header ">
                    <button class="accordion-button shadow-none px-0 fs-5 fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                      Step 1
                    </button>
                  </h2>
                  <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample1">
                    <div class="accordion-body px-0 fs-4">
                      Lorem ipsum dolor sit amet consectetur adipisicing elit. Eos laudantium unde aspernatur animi repellendus at nulla, nostrum nesciunt quasi ut!
                    </div>
                  </div>
                </div>
                <div class="accordion-item border-0 border-bottom">
                  <h2 class="accordion-header ">
                    <button class="accordion-button shadow-none collapsed px-0 fs-5 fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                      Step 2
                    </button>
                  </h2>
                  <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample1">
                    <div class="accordion-body px-0 fs-4">
                      Lorem ipsum dolor, sit amet consectetur adipisicing elit. Provident nisi perspiciatis magni ab possimus voluptatum commodi quisquam consequuntur dolores magnam.
                    </div>
                  </div>
                </div>
                <div class="accordion-item border-0 border-bottom">
                  <h2 class="accordion-header">
                    <button class="accordion-button shadow-none collapsed px-0 fs-5 fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                      Step 3
                    </button>
                  </h2>
                  <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionExample1">
                    <div class="accordion-body px-0 fs-4">
                      Lorem ipsum, dolor sit amet consectetur adipisicing elit. Et amet labore similique quas architecto natus, consequuntur suscipit fugit explicabo dignissimos!
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="payments-tab" tabindex="0">
        <div class="row gap-lg-0 gap-7">
          <div class="col-lg-6">
            <div class="bg-primary-subtle rounded-24 p-13">
              <img src="{{asset('assets/images/frontend-pages/tabsimage.png')}}" alt="icon" class="w-100">
            </div>
          </div>
          <div class="col-lg-6">
            <div class="d-flex flex-column h-100 justify-content-center align-items-start ps-lg-7">
              <h2 class="fs-10 fw-bolder mb-0">Payments</h2>
              <div class="accordion tabs-accordion my-4 w-100" id="accordionExample2">
                <div class="accordion-item border-0 border-bottom">
                  <h2 class="accordion-header ">
                    <button class="accordion-button shadow-none px-0 fs-5 fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                      Step 1
                    </button>
                  </h2>
                  <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample2">
                    <div class="accordion-body px-0 fs-4">
                      Lorem ipsum dolor sit amet consectetur adipisicing elit. Laboriosam quo et officiis culpa corrupti tempora iure ducimus ullam soluta distinctio!
                    </div>
                  </div>
                </div>
                <div class="accordion-item border-0 border-bottom">
                  <h2 class="accordion-header ">
                    <button class="accordion-button shadow-none collapsed px-0 fs-5 fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                      Step 2
                    </button>
                  </h2>
                  <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample2">
                    <div class="accordion-body px-0 fs-4">
                      Lorem ipsum dolor sit amet, consectetur adipisicing elit. Porro quod doloribus, ab aperiam consequatur hic repellendus culpa consectetur cum officia.
                    </div>
                  </div>
                </div>
                <div class="accordion-item border-0 border-bottom">
                  <h2 class="accordion-header">
                    <button class="accordion-button shadow-none collapsed px-0 fs-5 fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                      Step 3
                    </button>
                  </h2>
                  <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionExample2">
                    <div class="accordion-body px-0 fs-4">
                      Lorem ipsum dolor sit amet consectetur adipisicing elit. Autem inventore ratione culpa dolor, veniam reprehenderit quas ipsa labore qui placeat?
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="embedding-tab" tabindex="0">
        <div class="row gap-lg-0 gap-7">
          <div class="col-lg-6">
            <div class="bg-primary-subtle rounded-24 p-13">
              <img src="{{asset('assets/images/frontend-pages/tabsimage.png')}}" alt="icon" class="w-100">
            </div>
          </div>
          <div class="col-lg-6">
            <div class="d-flex flex-column h-100 justify-content-center align-items-start ps-lg-7">
              <h2 class="fs-10 fw-bolder mb-0">Konfirmasi Jadwal</h2>
              <div class="accordion tabs-accordion my-4 w-100" id="accordionExample3">
                <div class="accordion-item border-0 border-bottom">
                  <h2 class="accordion-header ">
                    <button class="accordion-button shadow-none px-0 fs-5 fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                      Step 1
                    </button>
                  </h2>
                  <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample3">
                    <div class="accordion-body px-0 fs-4">
                      Lorem ipsum dolor sit amet, consectetur adipisicing elit. Repellendus temporibus vel iure ipsa quo dicta corrupti et qui nulla facere.
                    </div>
                  </div>
                </div>
                <div class="accordion-item border-0 border-bottom">
                  <h2 class="accordion-header ">
                    <button class="accordion-button shadow-none collapsed px-0 fs-5 fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                      Step 2
                    </button>
                  </h2>
                  <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample3">
                    <div class="accordion-body px-0 fs-4">
                      Lorem, ipsum dolor sit amet consectetur adipisicing elit. Doloremque blanditiis facilis ratione a similique eos sunt culpa eum velit voluptates!
                    </div>
                  </div>
                </div>
                <div class="accordion-item border-0 border-bottom">
                  <h2 class="accordion-header">
                    <button class="accordion-button shadow-none collapsed px-0 fs-5 fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                      Step 3
                    </button>
                  </h2>
                  <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionExample3">
                    <div class="accordion-body px-0 fs-4">
                      Lorem ipsum dolor sit, amet consectetur adipisicing elit. Itaque eaque inventore in laboriosam distinctio ducimus eligendi necessitatibus accusantium rerum suscipit.
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="tab-pane fade" id="pills-disabled" role="tabpanel" aria-labelledby="workflows-tab" tabindex="0">
        <div class="row gap-lg-0 gap-7">
          <div class="col-lg-6">
            <div class="bg-primary-subtle rounded-24 p-13">
              <img src="{{asset('assets/images/frontend-pages/tabsimage.png')}}" alt="icon" class="w-100">
            </div>
          </div>
          <div class="col-lg-6">
            <div class="d-flex flex-column h-100 justify-content-center align-items-start ps-lg-7">
              <h2 class="fs-10 fw-bolder mb-0">Mulai Sesi</h2>
              <div class="accordion tabs-accordion my-4 w-100" id="accordionExample4">
                <div class="accordion-item border-0 border-bottom">
                  <h2 class="accordion-header ">
                    <button class="accordion-button shadow-none px-0 fs-5 fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                      Step 1
                    </button>
                  </h2>
                  <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample4">
                    <div class="accordion-body px-0 fs-4">
                      Lorem ipsum dolor sit amet consectetur adipisicing elit. Impedit ipsum porro odio quae esse, fugiat aut eveniet ab doloremque tenetur.
                    </div>
                  </div>
                </div>
                <div class="accordion-item border-0 border-bottom">
                  <h2 class="accordion-header ">
                    <button class="accordion-button shadow-none collapsed px-0 fs-5 fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                      Step 2
                    </button>
                  </h2>
                  <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample4">
                    <div class="accordion-body px-0 fs-4">
                      Lorem ipsum dolor sit amet consectetur adipisicing elit. Autem voluptas maiores fuga assumenda adipisci quas sed. Placeat ut rem provident.
                    </div>
                  </div>
                </div>
                <div class="accordion-item border-0 border-bottom">
                  <h2 class="accordion-header">
                    <button class="accordion-button shadow-none collapsed px-0 fs-5 fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                      Step 3
                    </button>
                  </h2>
                  <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionExample4">
                    <div class="accordion-body px-0 fs-4">
                      Lorem ipsum dolor sit amet consectetur adipisicing elit. Possimus esse sed obcaecati atque harum natus beatae dignissimos quasi dolorem quam.
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
</section>

<section class="py-5 bg-light-gray">
  <div class="container-fluid">
    <div class="owl-carousel leadership-carousel owl-theme mt-lg-5 mb-lg-7">
      <div class="item">
        <div class="meet-our-team position-relative rounded-3 overflow-hidden">
          <img src="{{asset('assets/images/frontend-pages/alex.jpg')}}" alt="leader" class="">
          <div class="leadership-card z-1 bg-white rounded py-3 px-8 mx-6 my-6 w-90 text-center">
            <h4 class="fs-5 fw-bold mb-8">Alex Martinez</h4>
            <p class="fs-3 mb-0">Dokter Umum</p>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>
@endsection

