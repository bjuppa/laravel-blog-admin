<label for="{{ $controlId = $controlId ?? (($idPrefix ?? '') . $name) }}">{{ $label ?? $name }}</label>
<div>
<input type="{{ $type ?? 'text' }}" value="{{ old($name, $value ?? $slot ?? isset($model) ? $model->$name : '') }}"
  @include('blog-admin::forms.partials.inputAttributes', ['errorsId' => ($errorsId = $controlId . 'Errors')])
>
@include('blog-admin::forms.partials.errors', ['errorsId' => $errorsId])
</div>
