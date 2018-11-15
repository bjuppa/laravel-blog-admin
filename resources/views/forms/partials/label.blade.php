<label for="{{ $controlId }}">{!! $label ?? ucfirst(Lang::has('validation.attributes.'.$name) ? Lang::trans('validation.attributes.'.$name) : str_replace('_', ' ', $name)) !!}</label>
