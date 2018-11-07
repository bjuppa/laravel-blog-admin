@inject('view_manager', 'Kontenta\Kontour\Contracts\AdminViewManager')

@extends($view_manager->toolLayout())

@section($view_manager->toolHeaderSection())
<h1>{{ $blog->getTitle() }}</h1>
@append

@section($view_manager->toolMainSection())
<a href="{{ route('blog-admin.entries.create', $blog->getId()) }}">{{ __('Create new blog entry in') }} {{ $blog->getTitle() }}</a>
<table>
@foreach($entries as $entry)
<tr>
  <td><a href="{{ route('blog-admin.entries.edit', $entry->getId()) }}">{{ $entry->getTitle() }}</a></td>
</tr>
@endforeach
</table>
@append
