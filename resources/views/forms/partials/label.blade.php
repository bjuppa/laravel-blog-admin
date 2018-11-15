<{{ $labelTag = $labelTag ?? 'label' }}
@if(isset($controlId))
  for="{{ $controlId }}"
@endif
>{!! $labelStart ?? '' !!}{!! $label ?? ucfirst(Lang::has('validation.attributes.'.$name) ? Lang::trans('validation.attributes.'.$name) : str_replace('_', ' ', $name)) !!}{!! $labelEnd ?? '' !!}</{{ $labelTag }}>