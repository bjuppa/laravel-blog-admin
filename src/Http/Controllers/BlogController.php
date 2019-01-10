<?php

namespace Bjuppa\LaravelBlogAdmin\Http\Controllers;

use Bjuppa\LaravelBlog\Contracts\BlogRegistry;
use Illuminate\Routing\Controller as BaseController;
use Kontenta\Kontour\Concerns\AuthorizesAdminRequests;
use Kontenta\Kontour\Concerns\RegistersAdminWidgets;
use Kontenta\Kontour\Contracts\MessageWidget;

class BlogController extends BaseController
{
    use RegistersAdminWidgets, AuthorizesAdminRequests;

    protected $blogRegistry;

    public function __construct(BlogRegistry $blogRegistry)
    {
        $this->blogRegistry = $blogRegistry;

        $this->messages = $this->findOrRegisterAdminWidget(MessageWidget::class, 'kontourToolHeader');
    }

    public function show($id)
    {
        abort_unless($this->blogRegistry->has($id), 404);
        $blog = $this->blogRegistry->get($id);
        $this->authorizeShowAdminVisit('manage blog', 'Blog ' . $id, $blog->getTitle(), $id);

        $entryProvider = $blog->getEntryProvider();

        $entries = $entryProvider->getBuilder()->withUnpublished()->get();

        if (!$entryProvider instanceof \Bjuppa\LaravelBlog\Eloquent\BlogEntryProvider) {
            $this->messages->addMessage("Blog '" . $blog->getId() . "' is not configured with the Eloquent entry provider", 'error');
        }

        return view('blog-admin::blog.show', compact('blog', 'entries'));
    }
}
