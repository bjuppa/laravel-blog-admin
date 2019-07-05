<?php

namespace Bjuppa\LaravelBlogAdmin\Http\Controllers;

use Bjuppa\LaravelBlogAdmin\Http\Requests\BlogEntryRequest;
use Bjuppa\LaravelBlog\Contracts\Blog;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Kontenta\Kontour\AdminLink;
use Kontenta\Kontour\Concerns\AuthorizesAdminRequests;
use Kontenta\Kontour\Concerns\RegistersAdminWidgets;
use Kontenta\Kontour\Contracts\CrumbtrailWidget;
use Kontenta\Kontour\Contracts\ItemHistoryWidget;
use Kontenta\Kontour\Contracts\MessageWidget;

class BlogEntryController extends BaseController
{
    use RegistersAdminWidgets, AuthorizesAdminRequests;

    public function __construct()
    {
        $this->crumbtrail = $this->findOrRegisterAdminWidget(CrumbtrailWidget::class, 'kontourToolHeader');
        $this->messages = $this->findOrRegisterAdminWidget(MessageWidget::class, 'kontourToolHeader');
    }

    public function create(BlogEntryRequest $request)
    {
        $this->authorizeEditAdminVisit(
            $request->blog->getCreateAbility(),
            'New ' . $request->blog->getTitle() . ' entry',
            $request->blog->getTitle() . ': New entry',
            $request->blog->getId()
        );

        $this->buildCrumbtrail($request->blog, 'New entry');
        $this->addMessagesFromSession();

        return view('blog-admin::entry.create', ['entry' => $request->entry, 'blog' => $request->blog]);
    }

    public function store(BlogEntryRequest $request)
    {
        $request->entry->fill($request->validated())->save();

        return redirect(route('blog-admin.entries.edit', [$request->entry->getBlogId(), $request->entry->getKey()]))
            ->with('status', 'Blog entry created');
    }

    public function edit(BlogEntryRequest $request)
    {
        abort_unless($request->entry->exists, Response::HTTP_NOT_FOUND, 'Entry not found');
        $this->authorizeEditAdminVisit(
            $request->blog->getEditAbility(),
            $request->entry->getTitle(),
            $request->blog->getTitle() . ': ' . $request->entry->getTitle(),
            $request->entry
        );

        $this->buildCrumbtrail($request->blog, $request->entry->getTitle());
        $this->addMessagesFromSession();

        $itemHistoryWidget = $this->findOrRegisterAdminWidget(ItemHistoryWidget::class, 'kontourToolWidgets');
        $itemHistoryWidget->addCreatedEntry($request->entry->getAttribute($request->entry->getCreatedAtColumn()));
        $itemHistoryWidget->addUpdatedEntry($request->entry->getAttribute($request->entry->getUpdatedAtColumn()));

        return view('blog-admin::entry.edit', [
            'entry' => $request->entry,
            'blog' => $request->blog,
            'blog_options' => $request->getValidBlogsForCurrentUser()->mapWithKeys(function ($blog) {
                return [$blog->getId() => $blog->getTitle()];
            })->all(),
        ]);
    }

    public function update(BlogEntryRequest $request)
    {
        $request->entry->update($request->validated());
        $request->entry->refresh();

        return redirect(route('blog-admin.entries.edit', [$request->entry->getBlogId(), $request->entry->getKey()]))
            ->with('status', 'Save successful');
    }

    public function destroy(BlogEntryRequest $request)
    {
        $request->entry->delete();

        return redirect(route('blog-admin.blogs.show', [$request->entry->getBlogId()]))
            ->with('status', 'Deleted entry ' . $request->entry->getTitle());
    }

    protected function buildCrumbtrail(Blog $blog, $currentPageName)
    {
        $this->crumbtrail->addLink(AdminLink::create($blog->getTitle(), route('blog-admin.blogs.show', $blog->getId())));
        $this->crumbtrail->addLink(AdminLink::create($currentPageName, url()->current()));
    }

    protected function addMessagesFromSession()
    {
        $this->messages->addMessageIfSessionHasErrors('Validation failed, please correct the form input');
        $this->messages->addFromSession();
    }
}
