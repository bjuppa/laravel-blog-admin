<div>
  @include('blog-admin::forms.label', ['controlId' => $controlId = $controlId ?? (($idPrefix ?? '') . $name)])
  <div>
    <input type="{{ $type ?? 'text' }}"
      value="{{ old($name, $value ?? $slot ?? $model[$name] ?? '') }}"
      @include('blog-admin::forms.partials.inputAttributes', ['errorsId' => $errorsId = $controlId . ($errorsSuffix ?? 'Errors')])
    >
    @include('blog-admin::forms.partials.errors', ['errorsId' => $errorsId])
  </div>
</div>