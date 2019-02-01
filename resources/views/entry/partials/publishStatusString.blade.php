@if($entry->isPublic())
  Public since {{ $entry->getPublished() }}
@elseif($entry->getPublished()->isFuture())
  Scheduled for publishing {{ $entry->getPublished() }}
@else
  Not scheduled for publishing
@endif