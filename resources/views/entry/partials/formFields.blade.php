<input type="hidden" name="blog" value="{{ $entry->blog }}">
@include('kontour::forms.input', ['name' => 'title', 'controlAttributes' => ['required']])

@if($entry->exists)
  @if(count($blog_options) > 1)
    @include('kontour::forms.select', ['name' => 'blog', 'options' => $blog_options])
  @endif
  @include('kontour::forms.input', ['name' => 'slug'])
  @include('kontour::forms.input', [
    'name' => 'publish_after',
    'afterControl' => $blog->getTimezone()->getName(),
    'value' => $model['publish_after'] ? $blog->convertToBlogTimezone($model['publish_after'])->format('Y-m-d\TH:i') : '',
    'type' => 'datetime-local',
  ])
@endif

@include('kontour::forms.textarea', ['name' => 'content', 'controlAttributes' => ['required']])
@include('kontour::forms.radiobuttons', ['name' => 'display_full_content_in_feed', 'options' => [
  '' => 'Blog default ('. ($blog->displayFullEntryInFeed() ? 'full content' : 'summary or teaser') .')',
  1 => 'Display full content',
  0 => 'Display summary or teaser with a link to read more'
]])
@include('kontour::forms.textarea', ['name' => 'summary'])
@include('kontour::forms.textarea', ['name' => 'image'])
<samp>{{ $entry->getImage() }}</samp>

<fieldset>
  <legend>Author</legend>
  @include('kontour::forms.input', ['name' => 'author_name'])
  @include('kontour::forms.input', ['name' => 'author_email', 'type' => 'email'])
  @include('kontour::forms.input', ['name' => 'author_url'])
</fieldset>

@if($entry->exists)
  <fieldset>
    <legend>Meta-data</legend>
    @include('kontour::forms.input', ['name' => 'page_title'])
    @include('kontour::forms.input', ['name' => 'description'])
    @include('kontour::forms.textarea', ['name' => 'json_meta_tags'])
    <pre style="white-space: pre-wrap; word-break: break-all;"><samp>{{ $blog->getDefaultMetaTags()->merge($entry)->toHtml() }}</samp></pre>
  </fieldset>
@endif
