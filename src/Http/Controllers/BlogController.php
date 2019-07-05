<?php

namespace Bjuppa\LaravelBlogAdmin\Http\Controllers;

use Bjuppa\LaravelBlog\Contracts\BlogRegistry;
use Illuminate\Http\Response;
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
        abort_unless($this->blogRegistry->has($id), Response::HTTP_NOT_FOUND);
        $blog = $this->blogRegistry->get($id);

        $entryProvider = $blog->getEntryProvider();
        abort_unless(
            $entryProvider instanceof \Bjuppa\LaravelBlog\Eloquent\BlogEntryProvider,
            Response::HTTP_INTERNAL_SERVER_ERROR,
            'Blog "' . e($blog->getId()) . '" is not configured with the Eloquent entry provider'
        );

        $this->authorizeShowAdminVisit(
            $blog->getMainAbility(),
            $blog->getTitle(),
            'Blog: ' . $blog->getTitle(),
            $blog->getId()
        );

        $this->messages->addErrorsFromSession();
        $this->messages->addFromSession();

        $entries = $entryProvider->getBuilder()->withUnpublished()->latestPublication()->get();

        return view('blog-admin::blog.show', compact('blog', 'entries'));
    }
}
