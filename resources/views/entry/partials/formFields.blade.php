<label for="entry.title">Title</label>
<div>
<input id="entry.title" type="text" name="title" value="{{ old('title', $entry->title) }}" required @if($errors->has('title')) aria-invalid="true" aria-describedby="entry.title.errors" @endif>
@if($errors->has('title'))
<ul id="entry.title.errors">
  @foreach($errors->get('title') as $message)
    <li>{{ $message }}</li>
  @endforeach
</ul>
@endif
</div>

<label for="entry.content">Content</label>
<div>
<textarea id="entry.content" name="content" required @if($errors->has('content')) aria-invalid="true" aria-describedby="entry.content.errors" @endif>{{ old('content', $entry->content) }}</textarea>
@if($errors->has('content'))
<ul id="entry.content.errors">
  @foreach($errors->get('content') as $message)
    <li>{{ $message }}</li>
  @endforeach
</ul>
@endif
</div>
