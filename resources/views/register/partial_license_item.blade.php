<div data-repeater-item class="row">
  <div class="col-12 d-flex justify-content-end align-items-center mb-1">
    <span data-repeater-delete type="button" class="badge text-bg-light text-danger fs-2 p-1 py-0 fw-semibold bottom-0 end-0">Remove</span>
  </div>
  <div class="col-md-12 mb-2">
    <textarea name="certification" class="form-control" rows="1">{{ $certification }}</textarea>
    <p class="fs-2 mb-1">Your certification licensed</p>

    <div class="d-flex align-items-center gap-6 py-1 ps-1 mt-1 flex-nowrap">
      <i class="ti ti-file-description fs-6 d-block m-0"></i>
      <input name="attachment" class="form-control form-control-sm" type="file">
    </div>

    <p class="fs-2 mb-1" data-conditional-attachment>
      @if (!empty($attachment))
        <a target="_blank" class="text-muted" href="{{ urlpathSTORAGE($attachment) }}">
          <b>Old attachment</b>: {{ basename($attachment) }}
        </a>
      @else
        File attachment
      @endif
    </p>
  </div>
</div>
