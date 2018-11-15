@include('blog-admin::forms.input', ['name' => 'title', 'controlAttributes' => ['required']])

@if($entry->exists)
  @include('blog-admin::forms.input', ['name' => 'slug'])
@endif

@include('blog-admin::forms.input', ['name' => 'publish_after', 'type' => 'datetime-local'])

<fieldset>
  <legend>Author</legend>
  @include('blog-admin::forms.input', ['name' => 'author_name'])
  @include('blog-admin::forms.input', ['name' => 'author_email'])
  @include('blog-admin::forms.input', ['name' => 'author_url'])
</fieldset>

@include('blog-admin::forms.textarea', ['name' => 'image'])
<samp>{{ $entry->getImage() }}</samp>

@include('blog-admin::forms.textarea', ['name' => 'content', 'controlAttributes' => ['required']])

@if($entry->exists)
  @include('blog-admin::forms.textarea', ['name' => 'summary'])

  <fieldset>
    <legend>Meta-data</legend>
    @include('blog-admin::forms.input', ['name' => 'page_title'])
    @include('blog-admin::forms.input', ['name' => 'description'])
    @include('blog-admin::forms.textarea', ['name' => 'json_meta_tags'])
    <pre><samp>{{ $blog->getDefaultMetaTags()->merge($entry)->toHtml() }}</samp></pre>
  </fieldset>
@endif
