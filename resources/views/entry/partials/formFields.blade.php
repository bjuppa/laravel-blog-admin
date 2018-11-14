@include('blog-admin::forms.input', ['name' => 'title', 'label' => 'Title', 'controlAttributes' => ['required']])
@include('blog-admin::forms.input', ['name' => 'slug', 'label' => 'Slug'])
@include('blog-admin::forms.input', ['name' => 'publish_after', 'type' => 'datetime-local', 'label' => 'Publish after'])
@include('blog-admin::forms.textarea', ['name' => 'content', 'label' => 'Content', 'controlAttributes' => ['required']])
