<input type="hidden" name="blog" value="{{ $entry->blog }}">
@include('kontour::forms.input', ['name' => 'title', 'controlAttributes' => ['required']])

@if($entry->exists)
  @if(count($blog_options) > 1 and !$entry->isPublic())
    @include('kontour::forms.select', ['name' => 'blog', 'options' => $blog_options])
  @endif
  @include('kontour::forms.input', ['name' => 'slug'])
  @include('kontour::forms.input', [
    'name' => 'publish_after',
    'afterControl' => $blog->getTimezone()->getName(),
    'value' => $model['publish_after'] ? $blog->convertToBlogTimezone($model['publish_after'])->format('Y-m-d\TH:i') : '',
    'type' => 'datetime-local',
    'controlAttributes' => ['size' => "16"]
  ])
@endif

@include('kontour::forms.textarea', [
  'name' => 'content',
  'controlAttributes' => [
    'required',
    'rows' => ceil(max(strlen($model->content) / 65 + substr_count($model->content, "\n"), 20)),
    'style' => 'max-height: 80vh;',
  ],
])
@include('kontour::forms.radiobuttons', ['name' => 'display_full_content_in_feed', 'options' => [
  '' => 'Blog default ('. ($blog->displayFullEntryInFeed() ? 'full content' : 'summary or teaser') .')',
  1 => 'Display full content',
  0 => 'Display summary or teaser with a link to read more'
]])
@include('kontour::forms.textarea', [
  'name' => 'summary',
  'controlAttributes' => [
    'rows' => ceil(max(strlen($model->summary) / 65 + substr_count($model->summary, "\n"), 7)),
    'style' => 'max-height: 80vh;',
  ],
])
@include('kontour::forms.textarea', [
  'name' => 'image',
  'controlAttributes' => [
    'rows' => ceil(max(strlen($model->image) / 65 + substr_count($model->image, "\n"), 3)),
    'style' => 'max-height: 80vh;',
  ],
])
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
    @include('kontour::forms.textarea', [
      'name' => 'description',
      'controlAttributes' => [
        'rows' => ceil(max(strlen($model->description) / 65, 2)),
        'style' => 'max-height: 20vh;',
      ],
    ])
    @include('kontour::forms.textarea', [
      'name' => 'json_meta_tags',
      'value' => $model->json_meta_tags == 'null' ? "[{\n\"\": \"\"\n}]" : $model->json_meta_tags,
      'controlAttributes' => [
        'rows' => ceil(max(substr_count($model->json_meta_tags, "\n") + 1, 3)),
        'style' => 'max-height: 80vh;',
      ],
    ])
    <pre style="white-space: pre-wrap; word-break: break-all;"><samp>{{ $blog->getDefaultMetaTags()->merge($entry)->toHtml() }}</samp></pre>
  </fieldset>
@endif
