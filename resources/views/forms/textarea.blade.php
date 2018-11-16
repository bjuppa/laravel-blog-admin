<div>
  @include('blog-admin::forms.label', ['controlId' => $controlId = $controlId ?? (($idPrefix ?? '') . $name)])
  <div>
    <textarea
      @include('blog-admin::forms.partials.inputAttributes', ['errorsId' => $errorsId = $controlId . ($errorsSuffix ?? 'Errors')])
    >{{ old($name, $value ?? $slot ?? $model[$name] ?? '') }}</textarea>
    @include('blog-admin::forms.partials.errors', ['errorsId' => $errorsId])
  </div>
</div>