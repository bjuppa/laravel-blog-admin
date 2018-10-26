<?php

namespace Bjuppa\LaravelBlogAdmin\Http\Controllers;

use Bjuppa\LaravelBlog\Eloquent\BlogEntry;
use Illuminate\Routing\Controller as BaseController;

class EntryController extends BaseController
{
    public function edit($id)
    {
        $entry = BlogEntry::withUnpublished()->findOrFail($id);

        return view('blog-admin::entry.edit', compact('entry'));
    }

    public function update($id)
    {
        $entry = BlogEntry::withUnpublished()->findOrFail($id);

        return redirect(route('blog-admin.entries.edit', $entry->getKey()));
    }
}
