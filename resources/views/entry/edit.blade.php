@inject('view_manager', 'Kontenta\Kontour\Contracts\AdminViewManager')

@extends($view_manager->toolLayout())

@section($view_manager->toolMainSection())
Status:
@if($entry->isPublic())
  Public since {{ $entry->getPublished() }}
@elseif($entry->getPublished()->isFuture())
  Scheduled for publishing {{ $entry->getPublished() }}
@else
  Not scheduled for publishing
@endif

<form action="{{ route('blog-admin.entries.update', $entry->getKey()) }}" method="POST">
@method('PUT')
@csrf
@include('blog-admin::entry.partials.formFields', ['model' => $entry])
<button type="submit">{{ __('Save changes to blog entry') }}</button>
</form>
@append
