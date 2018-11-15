@include('blog-admin::forms.input', ['name' => 'title', 'controlAttributes' => ['required']])

@if($entry->exists)
  @include('blog-admin::forms.select', ['name' => 'blog', 'options' => $blog_options])
  @include('blog-admin::forms.input', ['name' => 'slug'])
  @include('blog-admin::forms.input', ['name' => 'publish_after', 'type' => 'datetime-local'])
@endif

@include('blog-admin::forms.textarea', ['name' => 'content', 'controlAttributes' => ['required']])
@include('blog-admin::forms.radiobuttons', ['name' => 'display_full_content_in_feed', 'options' => ['' => 'Blog default', '1' => 'Display full content', '0' => 'Display summary or teaser with a link to read more']])
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
