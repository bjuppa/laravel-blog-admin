<?php

namespace Bjuppa\LaravelBlogAdmin\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Bjuppa\LaravelBlog\Contracts\BlogRegistry;

class BlogController extends BaseController
{
    protected $blogRegistry;

    public function __construct(BlogRegistry $blogRegistry) {
        $this->blogRegistry = $blogRegistry;
    }

    /**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        abort_unless($this->blogRegistry->has($id), 404);

        $blog = $this->blogRegistry->get($id);
        $entryProvider = $blog->getEntryProvider();
        //TODO: Put warning in message widget if blog does not have a \Bjuppa\LaravelBlog\Eloquent\BlogEntryProvider

        $entries = $entryProvider->getBuilder()->withUnpublished()->get();

        return view('blog-admin::blog.show', compact('blog', 'entries'));
    }
}
