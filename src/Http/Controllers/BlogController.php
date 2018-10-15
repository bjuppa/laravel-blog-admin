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
        $blog = $this->blogRegistry->get($id);

        abort_unless($blog, 404);

        return view('blog-admin::blog.show', compact('blog'));
    }
}
