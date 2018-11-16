<fieldset data-checked-radio="{{ $selected = old($name, $selected ?? $model[$name] ?? '') ?? '' }}">
  @include('blog-admin::forms.label', ['labelTag' => 'legend'])
  @foreach($options as $option_value => $option_display)
    @component('blog-admin::forms.label', ['label' => $option_display])
      @slot('labelStart')
        <input type="radio"
          @if($selected === $option_value)
            checked
          @endif
          value="{{ $option_value }}"
          @include('blog-admin::forms.partials.inputAttributes', ['controlId' => $controlId = $controlId ?? (($idPrefix ?? '') . $name), 'errorsId' => $errorsId = $controlId . ($errorsSuffix ?? 'Errors'), 'controlId' => $controlId . $option_value])
        >
      @endslot
    @endcomponent
  @endforeach
  @include('blog-admin::forms.partials.errors', ['errorsId' => $errorsId])
</fieldset>