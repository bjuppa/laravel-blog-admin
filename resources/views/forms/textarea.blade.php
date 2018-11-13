@include('blog-admin::forms.partials.label', ['controlId' => $controlId = $controlId ?? (($idPrefix ?? '') . $name)])
<div>
<textarea
  @include('blog-admin::forms.partials.inputAttributes', ['errorsId' => $errorsId = $controlId . ($errorsSuffix ?? 'Errors')])
>{{ old($name, $value ?? $slot ?? isset($model) ? $model->$name : '') }}</textarea>
@include('blog-admin::forms.partials.errors', ['errorsId' => $errorsId])
</div>
