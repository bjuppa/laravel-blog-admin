@inject('view_manager', 'Kontenta\Kontour\Contracts\AdminViewManager')

@extends($view_manager->toolLayout())

@section('kontourToolHeader')
  @parent
  <p lang="en">
    <small>
    @include('blog-admin::entry.partials.publishStatusString')
    at
    <a
      @if($entry->isPublic() or Auth::user()->can($blog->getPreviewAbility(), $entry))
        href="{{ $blog->urlToEntry($entry) }}"
      @endif
      target="{{ $blog->getId() }}"
    >{{ $blog->urlToEntry($entry) }}</a>
    </small>
  </p>
@endsection

@section('kontourToolMain')
  <form action="{{ route('blog-admin.entries.update', $entry->getKey()) }}" method="POST">
    @method('PATCH')
    @csrf
    @include('blog-admin::entry.partials.formFields', ['model' => $entry])
    <div data-kontour-section="kontourStickyActions">
      @include('kontour::buttons.update', ['description' => __('Save changes to blog entry')])
    </div>
  </form>
@endsection

@section('kontourToolFooter')
  @unless($entry->isPublic() or $entry->getPublished()->isFuture())
    <form action="{{ route('blog-admin.entries.destroy', [$entry->getBlogId(), $entry->getKey()]) }}" method="POST">
      @method('DELETE')
      @csrf
      @include('kontour::buttons.destroy', ['description' => __('Delete blog entry') . ' ' . $entry->getTitle()])
    </form>
  @endunless
  @parent
@endsection