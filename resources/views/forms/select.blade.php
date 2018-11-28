<div data-selected-option="{{ $selected = strval(old($name, $selected ?? $model[$name] ?? '')) }}">
  @include('blog-admin::forms.label', ['controlId' => $controlId = $controlId ?? (($idPrefix ?? '') . $name)])
  <div>
    <select
      @include('blog-admin::forms.partials.inputAttributes', ['errorsId' => $errorsId = $controlId . ($errorsSuffix ?? 'Errors')])
    >
      @foreach($options as $option_value => $option_display)
        @if($optgroup = is_iterable($option_display) ? $option_value : false)
        <optgroup label="{{ $optgroup }}">
        @endif
        @foreach($optgroup ? $option_display : [$option_value => $option_display] as $option_value => $option_display)
        <option
          @if($selected == strval($option_value))
            selected
          @endif
          value="{{ $option_value }}"
        >{!! $option_display !!}</option>
        @endforeach
        @if($optgroup)
        </optgroup>
        @endif
      @endforeach
    </select>
    @include('blog-admin::forms.partials.errors', ['errorsId' => $errorsId])
  </div>
</div>