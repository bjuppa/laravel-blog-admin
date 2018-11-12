<?php

namespace Bjuppa\LaravelBlogAdmin\Http\Controllers;

use Bjuppa\LaravelBlog\Eloquent\BlogEntry;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Bjuppa\LaravelBlogAdmin\Http\Requests\StoreBlogEntry;
use Bjuppa\LaravelBlogAdmin\Http\Requests\UpdateBlogEntry;

//TODO: rename EntryController to BlogEntryController
class EntryController extends BaseController
{
    public function create($blog)
    {
        $entry = new BlogEntry();
        $entry->blog = $blog;

        return view('blog-admin::entry.create', compact('entry'));
    }

    public function store(StoreBlogEntry $request)
    {
        $entry = BlogEntry::create($request->all());

        return redirect(route('blog-admin.entries.edit', $entry->getKey()));
    }

    public function edit($id)
    {
        $entry = BlogEntry::withUnpublished()->findOrFail($id);

        return view('blog-admin::entry.edit', compact('entry'));
    }

    public function update(UpdateBlogEntry $request, $id)
    {
        $entry = BlogEntry::withUnpublished()->findOrFail($id);

        $entry->update($request->all());

        return redirect(route('blog-admin.entries.edit', $entry->getKey()));
    }
}
