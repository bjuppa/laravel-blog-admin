@inject('view_manager', 'Kontenta\Kontour\Contracts\AdminViewManager')

@extends($view_manager->toolLayout())

@section('kontourToolHeader')
  <h1>{{ $blog->getTitle() }}</h1>
  @parent
  @can($blog->getCreateAbility(), $blog->getId())
    <a href="{{ route('blog-admin.entries.create', $blog->getId()) }}">{{ __('Create new blog entry in') }} {{ $blog->getTitle() }}</a>
  @endcan
@endsection

@section('kontourToolMain')
<table>
@foreach($entries as $entry)
<tr>
  @can($blog->getEditAbility(), $entry)
    <td><a href="{{ route('blog-admin.entries.edit', [$blog->getId(), $entry->getId()]) }}">{{ $entry->getTitle() }}</a></td>
  @endcan
</tr>
@endforeach
</table>
@append
