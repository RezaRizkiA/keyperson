<div data-repeater-item class="col-md-6 mt-3">
    <div class="d-flex justify-content-between align-items-center">
        <input name="photos" type="file" class="form-control form-control-sm">
        <button data-repeater-delete class="btn btn-danger btn-sm px-2 ms-1" type="button">
            <i class="ti ti-circle-x fs-5 d-flex"></i>
        </button>
    </div>
    <p class="fs-2 mb-1" data-conditional-attachment>
        @if (!empty($photo))
            <a target="_blank" class="text-muted" href="{{ urlpathSTORAGE($photo) }}">
                <b>Old attachment</b>: {{ basename($photo) }}
            </a>
        @else
            Attachment
        @endif
    </p>
</div>
