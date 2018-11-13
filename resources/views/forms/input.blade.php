<label for="{{ $controlId = $controlId ?? (($idPrefix ?? '') . $name) }}">{{ $label ?? $name }}</label>
<div>
<input
  type="{{ $type ?? 'text' }}"
  value="{{ old($name, $value ?? $slot ?? isset($model) ? $model->$name : '') }}"
  name="{{ $name }}"
  id="{{ $controlId }}"
  @if($errors->has($name))
    aria-invalid="true"
    aria-describedby="{{ $errorId = $controlId . 'Errors' }}"
  @elseif(isset($ariaDescribedById))
    aria-describedby="{{ $ariaDescribedById }}"
  @endif
  @if(isset($controlAttributes))
    @if(is_array($controlAttributes))
      @foreach($controlAttributes as $attributeName => $attributeValue)
        @if(is_int($attributeName))
          {{ $attributeValue }}
        @else
          {{ $attributeName }}="{{ $attributeValue }}"
        @endif
      @endforeach
    @else
      {!! $controlAttributes !!}
    @endif
  @endif
>
@if($errors->has($name))
<ul id="{{ $errorId }}">
  @foreach($errors->get($name) as $message)
    <li>{{ $message }}</li>
  @endforeach
</ul>
@endif
</div>
