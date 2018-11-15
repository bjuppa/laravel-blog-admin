<{{ $labelTag = $labelTag ?? 'label' }}
  @if(isset($controlId))
    for="{{ $controlId }}"
  @endif
>{{ $slot ?? '' }}{!! $label ?? ucfirst(Lang::has('validation.attributes.'.$name) ? Lang::trans('validation.attributes.'.$name) : str_replace('_', ' ', $name)) !!}</{{ $labelTag }}>
