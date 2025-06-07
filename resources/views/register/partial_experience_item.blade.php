<div data-repeater-item class="row">
  <div class="col-12 d-flex justify-content-end align-items-center mb-1">
    <span data-repeater-delete type="button" class="badge text-bg-light text-danger fs-2 p-1 py-0 fw-semibold bottom-0 end-0">Remove</span>
  </div>

  <div class="col-md-4 mb-1">
    <input name="position" type="text" class="form-control" value="{{ $position }}">
    <p class="fs-2 mb-1">Your job title or role</p>
  </div>

  <div class="col-md-4 mb-1">
    <input name="company" type="text" class="form-control" value="{{ $company }}">
    <p class="fs-2 mb-1">Company or organization name</p>
  </div>

  <div class="col-md-4 mb-1">
    <input name="years" type="text" class="form-control" value="{{ $years }}">
    <p class="fs-2 mb-1">Enter the start to end year</p>
  </div>

  <div class="col-md-12 mb-1">
    <textarea name="description" class="form-control" rows="1">{{ $description }}</textarea>
    <p class="fs-2 mb-1">Main tasks and responsibilities</p>
  </div>
</div>
