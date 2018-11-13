@include('blog-admin::forms.input', ['name' => 'title'])

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
