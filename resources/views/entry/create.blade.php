@inject('view_manager', 'Kontenta\Kontour\Contracts\AdminViewManager')

@extends($view_manager->toolLayout())

@section('kontourToolMain')
<form action="{{ route('blog-admin.entries.store') }}" method="POST">
@csrf
@include('blog-admin::entry.partials.formFields', ['model' => $entry])
<div data-kontour-section="kontourStickyActions">
  @include('kontour::buttons.create', ['description' => __('Create new blog entry')])
</div>
</form>
@append
