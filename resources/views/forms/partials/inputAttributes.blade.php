name="{{ $name }}"
id="{{ $controlId }}"
@if($errors->has($name))
  aria-invalid="true"
  aria-describedby="{{ $errorsId }}"
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