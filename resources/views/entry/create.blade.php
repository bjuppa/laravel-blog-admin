@inject('view_manager', 'Kontenta\Kontour\Contracts\AdminViewManager')

@extends($view_manager->toolLayout())

@section('kontourToolMain')
<form action="{{ route('blog-admin.entries.store') }}" method="POST">
@csrf
<input type="hidden" name="blog" value="{{ $entry->blog }}">
@include('blog-admin::entry.partials.formFields', ['model' => $entry])
<button type="submit">{{ __('Create new blog entry') }}</button>
</form>
@append
