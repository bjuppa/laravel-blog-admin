@inject('view_manager', 'Kontenta\Kontour\Contracts\AdminViewManager')

@extends($view_manager->layout())

@section($view_manager->mainSection())
<h1>{{ $blog->getTitle()}}</h1>
@append
