<div>
  <label><input type="checkbox"
    @if(old($name, $checked ?? $model[$name] ?? false))
      checked
    @endif
    value="{{ $value ?? $checkbox_default_value ?? '1' }}"
    @include('blog-admin::forms.partials.inputAttributes', ['controlId' => $controlId = $controlId ?? (($idPrefix ?? '') . $name), 'errorsId' => $errorsId = $controlId . ($errorsSuffix ?? 'Errors')])
    >{!! $label ?? ucfirst(Lang::has('validation.attributes.'.$name) ? Lang::trans('validation.attributes.'.$name) : str_replace('_', ' ', $name)) !!}</label>
    @include('blog-admin::forms.partials.errors', ['errorsId' => $errorsId])
</div>
