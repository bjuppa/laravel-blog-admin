@inject('view_manager', 'Kontenta\Kontour\Contracts\AdminViewManager')

@extends($view_manager->toolLayout())

@section($view_manager->toolHeaderSection())

@append

@section($view_manager->toolMainSection())
<form>
@csrf
</form>
@append
