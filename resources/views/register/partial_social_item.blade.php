<div data-repeater-item class="col-12 mt-3 d-flex">
    <select name="key" class="form-select w-auto" width="30">
        <option value="facebook" {{ isset($key) && $key == 'facebook' ? 'selected' : '' }}>Facebook</option>
        <option value="instagram" {{ isset($key) && $key == 'instagram' ? 'selected' : '' }}>Instagram</option>
        <option value="youtube" {{ isset($key) && $key == 'youtube' ? 'selected' : '' }}>Youtube</option>
        <option value="linkedin" {{ isset($key) && $key == 'linkedin' ? 'selected' : '' }}>LinkedIn</option>
    </select>
    <input type="url" name="value" class="form-control ms-2" placeholder="Link profil" value="{{ $value ?? '' }}">
    <button data-repeater-delete class="btn btn-danger btn-sm px-2 ms-1" type="button">
        <i class="ti ti-circle-x fs-5 d-flex"></i>
    </button>
</div>

