<div data-repeater-item class="row mb-2 align-items-center">
    <div class="col-md-3">
        <select name="key" class="form-select" required>
            <option value="">Choose Social Media</option>
            <option value="facebook" {{ isset($key) && $key == 'facebook' ? 'selected' : '' }}>Facebook</option>
            <option value="instagram" {{ isset($key) && $key == 'instagram' ? 'selected' : '' }}>Instagram</option>
            <option value="youtube" {{ isset($key) && $key == 'youtube' ? 'selected' : '' }}>Youtube</option>
            <option value="linkedin" {{ isset($key) && $key == 'linkedin' ? 'selected' : '' }}>LinkedIn</option>
        </select>
    </div>
    <div class="col-md-7">
        <input type="url" name="value" class="form-control" placeholder="Link profil" value="{{ $value ?? '' }}"
            required>
    </div>
    <div class="col-md-2">
        <button data-repeater-delete class="btn btn-danger btn-sm px-2 ms-1" type="button">
            <i class="ti ti-circle-x fs-5 d-flex"></i>
        </button>
    </div>
</div>
