<fieldset id="{{ $groupId = $controlId ?? (($idPrefix ?? '') . $name) }}"
  data-checked-checkboxes="{{ implode(' ', $selected = collect(old($name, $selected ?? $model[$name] ?? []))->map(function($item) { return strval($item instanceof Illuminate\Database\Eloquent\Model ? $item->getKey() : $item); })->all()) }}"
>
  @include('blog-admin::forms.label', ['labelTag' => 'legend'])
  @foreach($options as $option_value => $option_display)
    @if($legend = is_iterable($option_display) ? $option_value : false)
    <fieldset><legend>{{ $legend }}</legend>
    @endif
    @foreach($legend ? $option_display : [$option_value => $option_display] as $option_value => $option_display)
    @component('blog-admin::forms.label', ['label' => $option_display, 'controlId' => $controlId = $groupId . '[' . $option_value . ']'])
      @slot('labelStart')
        <input type="checkbox"
          @if(in_array(strval($option_value), $selected))
            checked
          @endif
          value="{{ $option_value }}"
          @include('blog-admin::forms.partials.inputAttributes', ['name' => $name . '[]', 'errorsId' => $errorsId = $groupId . ($errorsSuffix ?? 'Errors')])
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