<fieldset data-checked-radio="{{ $selected = strval(old($name, $selected ?? $model[$name] ?? '')) }}">
  @include('blog-admin::forms.label', ['labelTag' => 'legend'])
  @foreach($options as $option_value => $option_display)
    @if($legend = is_iterable($option_display) ? $option_value : false)
    <fieldset><legend>{{ $legend }}</legend>
    @endif
    @foreach($legend ? $option_display : [$option_value => $option_display] as $option_value => $option_display)
    @component('blog-admin::forms.label', ['label' => $option_display])
      @slot('labelStart')
        <input type="radio"
          @if($selected == strval($option_value))
            checked
          @endif
          value="{{ $option_value }}"
          @include('blog-admin::forms.partials.inputAttributes', ['controlId' => $controlId = $controlId ?? (($idPrefix ?? '') . $name), 'errorsId' => $errorsId = $controlId . ($errorsSuffix ?? 'Errors'), 'controlId' => $controlId . $option_value])
        >
      @endslot
    @endcomponent
    @endforeach
    @if($legend)
    </fieldset>
    @endif
  @endforeach
  @include('blog-admin::forms.partials.errors', ['errorsId' => $errorsId])
</fieldset>