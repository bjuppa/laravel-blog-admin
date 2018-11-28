<?php

namespace Bjuppa\LaravelBlogAdmin\Http\Controllers;

use Bjuppa\LaravelBlogAdmin\Http\Requests\StoreBlogEntry;
use Bjuppa\LaravelBlogAdmin\Http\Requests\UpdateBlogEntry;
use Bjuppa\LaravelBlog\Contracts\BlogRegistry;
use Bjuppa\LaravelBlog\Eloquent\BlogEntry;
use Illuminate\Routing\Controller as BaseController;

//TODO: rename EntryController to BlogEntryController
class EntryController extends BaseController
{
    protected $blogRegistry;

    public function __construct(BlogRegistry $blogRegistry)
    {
        $this->blogRegistry = $blogRegistry;
    }

    public function create($blog_id)
    {
        $blog = $this->blogRegistry->get($blog_id);
        abort_unless($blog, 404, 'Blog "' . e($blog_id) . '" does not exist');

        $entry = new BlogEntry();
        $entry->blog = $blog->getId();

        return view('blog-admin::entry.create', compact('entry', 'blog'));
    }

    public function store(StoreBlogEntry $request)
    {
        $entry = BlogEntry::create($request->validated());

        return redirect(route('blog-admin.entries.edit', $entry->getKey()));
    }

    public function edit($id)
    {
        $entry = BlogEntry::withUnpublished()->findOrFail($id);
        $blog = $this->blogRegistry->get($entry->getBlogId());
        $blog_options = $this->blogRegistry->all()->filter(function ($blog) {
            return $blog->getEntryProvider() instanceof \Bjuppa\LaravelBlog\Eloquent\BlogEntryProvider;
        })->mapWithKeys(function ($blog) {
            return [$blog->getId() => $blog->getTitle()];
        })->all();

        return view('blog-admin::entry.edit', compact('entry', 'blog', 'blog_options'));
    }

    public function update(UpdateBlogEntry $request, $id)
    {
        $entry = BlogEntry::withUnpublished()->findOrFail($id);

        $entry->update($request->validated());

        return redirect(route('blog-admin.entries.edit', $entry->getKey()));
    }
}
