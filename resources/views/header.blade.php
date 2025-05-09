<header class="header-fp p-0 w-100 bg-light-gray">
  <nav class="navbar navbar-expand-lg py-10">
    <div class="container-fluid d-flex justify-content-between">
      <a href="" class="text-nowrap logo-img">
        <img src="{{asset('assets/images/logos/logo.svg')}}" alt="Logo" />
      </a>
      <button class="navbar-toggler border-0 p-0 shadow-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">
        <i class="ti ti-menu-2 fs-8"></i>
      </button>
      <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
        <ul class="navbar-nav mb-2 gap-xl-7 gap-8 mb-lg-0 me-5">
          <li class="nav-item">
            <a class="nav-link fs-4 fw-bold text-dark link-primary" href="">About Us</a>
          </li>
          <li class="nav-item">
            <a class="nav-link fs-4 fw-bold text-dark link-primary" href="">Dashboard</a>
          </li>
          <li class="nav-item">
            <a class="nav-link fs-4 fw-bold text-dark link-primary" href="">Contact</a>
          </li>
        </ul>
        <a href="" class="btn btn-dark btn-sm py-2 px-9">Log In</a>
      </div>
    </div>
  </nav>
</header>

<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
  <div class="offcanvas-header">
    <a href="" class="text-nowrap logo-img">
      <img src="{{asset('assets/images/logos/logo.svg')}}" alt="Logo" />
    </a>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
    <ul class="list-unstyled ps-0">
      <li class="mb-1">
        <a href="" class="px-0 fs-4 d-block text-dark link-primary w-100 py-2">
          About Us
        </a>
      </li>

      <li class="mb-1">
        <a href="" class="px-0 fs-4 d-block w-100 py-2 text-dark link-primary">
          Dashboard
        </a>
      </li>

      <li class="mb-1">
        <a href="" class="px-0 fs-4 d-block w-100 py-2 text-dark link-primary">
          Contact
        </a>
      </li>
      <li class="mt-3">
        <a href="" class="btn btn-primary w-100">Log In</a>
      </li>
    </ul>
  </div>
</div>