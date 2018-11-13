<label for="{{ $controlId = $controlId ?? (($idPrefix ?? '') . $name) }}">{{ $label ?? $name }}</label>
<div>
<input
  type="{{ $type ?? 'text' }}"
  value="{{ old($name, $value ?? $slot ?? isset($model) ? $model->$name : '') }}"
  @include('blog-admin::forms.partials.inputAttributes', ['errorsId' => ($errorsId = $controlId . 'Errors')])
>
@if($errors->has($name))
<ul id="{{ $errorsId }}">
  @foreach($errors->get($name) as $message)
    <li>{{ $message }}</li>
  @endforeach
</ul>
@endif
</div>
