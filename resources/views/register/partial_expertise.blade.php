<div class="card border position-relative overflow-hidden mb-0">
  <div class="card-body p-2" style="max-height: 1000px; overflow-y: auto;">
    @foreach ($expertises as $parents)
      <label class="form-label mb-1 text-uppercase">{{ $parents->name }}<span class="text-danger">*</span></label>
      <div class="to-do-widget common-widget accordion mb-3" id="expertise-{{ $parents->id }}">
        <ul class="list-task todo-list list-group mb-0" data-role="tasklist">
          @foreach ($parents->childrensRecursive as $childrens)
            <li class="list-group-item border-0 mb-0 pe-3 ps-0 accordion-item" data-role="task">
              <div class="form-check form-check-inline w-100" id="heading-{{ $childrens->id }}">
                <input type="radio" class="form-check-input danger check-light-danger" name="child" id="expertise-{{ $childrens->id }}" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $childrens->id }}" aria-expanded="true" aria-controls="collapse-{{ $childrens->id }}">
                <label for="expertise-{{ $childrens->id }}" class="form-check-label fw-medium"><span>{{ $childrens->name }}</span></label>
              </div>
              <ul class="list-style-none ms-2 m-0 mt-1 accordion-collapse collapse" id="collapse-{{ $childrens->id }}" aria-labelledby="heading-{{ $childrens->id }}" data-bs-parent="#expertise-{{ $parents->id }}">
                @foreach ($childrens->childrensRecursive as $grandchild)
                  <li class="list-group-item border-0 py-1">
                    <div class="form-check py-0">
                        <input type="checkbox" value="{{ $grandchild->id }}" name="expertise_id[]" class="form-check-input" id="{{ $grandchild->name }}" aria-invalid="false" @if ($expertiseSelected !== null && in_array($grandchild->id, $expertiseSelected)) checked @endif>
                        <label class="form-check-label font-weight-bold small" for="{{ $grandchild->name }}">{{ $grandchild->name }}</label>
                    </div>
                  </li>
                @endforeach
              </ul>
            </li>
          @endforeach
        </ul>
      </div>
    @endforeach
  </div>
</div>
