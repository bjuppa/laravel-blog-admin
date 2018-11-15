@include('blog-admin::forms.input', ['name' => 'title', 'controlAttributes' => ['required']])

@if($entry->exists)
  @include('blog-admin::forms.input', ['name' => 'slug'])
  @include('blog-admin::forms.input', ['name' => 'publish_after', 'type' => 'datetime-local'])
@endif

@include('blog-admin::forms.textarea', ['name' => 'content', 'controlAttributes' => ['required']])
@include('blog-admin::forms.checkbox', ['name' => 'display_full_content_in_feed'])
@include('blog-admin::forms.textarea', ['name' => 'summary'])
@include('blog-admin::forms.textarea', ['name' => 'image'])
<samp>{{ $entry->getImage() }}</samp>

<fieldset>
  <legend>Author</legend>
  @include('blog-admin::forms.input', ['name' => 'author_name'])
  @include('blog-admin::forms.input', ['name' => 'author_email'])
  @include('blog-admin::forms.input', ['name' => 'author_url'])
</fieldset>

@if($entry->exists)
  <fieldset>
    <legend>Meta-data</legend>
    @include('blog-admin::forms.input', ['name' => 'page_title'])
    @include('blog-admin::forms.input', ['name' => 'description'])
    @include('blog-admin::forms.textarea', ['name' => 'json_meta_tags'])
    <pre><samp>{{ $blog->getDefaultMetaTags()->merge($entry)->toHtml() }}</samp></pre>
  </fieldset>
@endif
