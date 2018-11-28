name="{{ $name }}"
id="{{ $controlId }}"
@if($errors->has(str_replace('[]', '', $name)))
  aria-invalid="true"
  aria-describedby="{{ $errorsId }}"
@elseif(isset($ariaDescribedById))
  aria-describedby="{{ $ariaDescribedById }}"
@endif
@if(isset($controlAttributes))
  @if(is_iterable($controlAttributes))
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