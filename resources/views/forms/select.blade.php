@include('blog-admin::forms.label', ['controlId' => $controlId = $controlId ?? (($idPrefix ?? '') . $name)])
<div>
  <select
    @include('blog-admin::forms.partials.inputAttributes', ['errorsId' => $errorsId = $controlId . ($errorsSuffix ?? 'Errors')])
  >
    @foreach($options as $option_value => $option_display)
      <option
            @if(old($name, $model[$name] ?? false) == $option_value)
              selected
            @endif
            value="{{ $option_value }}"
      >{!! $option_display !!}</option>
    @endforeach
  </select>
  @include('blog-admin::forms.partials.errors', ['errorsId' => $errorsId])
</div>