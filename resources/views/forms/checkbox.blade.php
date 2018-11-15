<div>
  @component('blog-admin::forms.partials.label', compact('name'))
    <input type="checkbox"
      @if(old($name, $checked ?? $model[$name] ?? false))
        checked
      @endif
      value="{{ $value ?? $checkbox_default_value ?? '1' }}"
      @include('blog-admin::forms.partials.inputAttributes', ['controlId' => $controlId = $controlId ?? (($idPrefix ?? '') . $name), 'errorsId' => $errorsId = $controlId . ($errorsSuffix ?? 'Errors')])
    >
    @endcomponent
    @include('blog-admin::forms.partials.errors', ['errorsId' => $errorsId])
</div>
