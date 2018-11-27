<div data-selected-options="{{ implode(' ', $selected = old($name, $selected ?? $model[$name] ?? []) ?? []) }}">
  @include('blog-admin::forms.label', ['controlId' => $controlId = $controlId ?? (($idPrefix ?? '') . $name)])
  <div>
    <select multiple
      @include('blog-admin::forms.partials.inputAttributes', ['name' => $name . '[]', 'errorsId' => $errorsId = $controlId . ($errorsSuffix ?? 'Errors')])
    >
      @foreach($options as $option_value => $option_display)
        <option
          @if(in_array($option_value, $selected))
            selected
          @endif
          value="{{ $option_value }}"
        >{!! $option_display !!}</option>
      @endforeach
    </select>
    @include('blog-admin::forms.partials.errors', ['errorsId' => $errorsId])
  </div>
</div>