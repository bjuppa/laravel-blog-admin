<fieldset data-checked-checkboxes="{{ implode(' ', $selected = collect(old($name, $selected ?? $model[$name] ?? []))->map(function($item) { return $item instanceof Illuminate\Database\Eloquent\Model ? $item->getKey() : (string) $item; })->all()) }}">
  @include('blog-admin::forms.label', ['labelTag' => 'legend'])
  @foreach($options as $option_value => $option_display)
    @component('blog-admin::forms.label', ['label' => $option_display])
      @slot('labelStart')
        <input type="checkbox"
          @if(in_array($option_value, $selected))
            checked
          @endif
          value="{{ $option_value }}"
          @include('blog-admin::forms.partials.inputAttributes', ['name' => $name . '[]', 'controlId' => $controlId = $controlId ?? (($idPrefix ?? '') . $name), 'errorsId' => $errorsId = $controlId . ($errorsSuffix ?? 'Errors'), 'controlId' => $controlId . $option_value])
        >
      @endslot
    @endcomponent
  @endforeach
  @include('blog-admin::forms.partials.errors', ['errorsId' => $errorsId])
</fieldset>