@inject('view_manager', 'Kontenta\Kontour\Contracts\AdminViewManager')

@extends($view_manager->toolLayout())

@section('kontourToolMain')

  <p lang="en">
    @if($entry->isPublic())
      Public since {{ $entry->getPublished() }}
    @elseif($entry->getPublished()->isFuture())
      Scheduled for publishing {{ $entry->getPublished() }}
    @else
      Not scheduled for publishing
    @endif
    at
    <a
      @if($entry->isPublic() or Auth::user()->can($blog->getPreviewAbility(), $entry))
        href="{{ $blog->urlToEntry($entry) }}"
      @endif
      target="_blank"
    >{{ $blog->urlToEntry($entry) }}</a>
  </p>

  <form action="{{ route('blog-admin.entries.update', $entry->getKey()) }}" method="POST">
    @method('PUT')
    @csrf
    @include('blog-admin::entry.partials.formFields', ['model' => $entry])
    <button type="submit">{{ __('Save changes to blog entry') }}</button>
  </form>

@append
