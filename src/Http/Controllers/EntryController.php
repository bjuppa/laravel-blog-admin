<?php

namespace Bjuppa\LaravelBlogAdmin\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Bjuppa\LaravelBlog\Eloquent\BlogEntry;

class EntryController extends BaseController
{
    public function edit($id)
    {
        $entry = BlogEntry::withUnpublished()->findOrFail($id);

        return view('blog-admin::entry.edit', compact('entry'));
    }
}
