<?php

namespace Bjuppa\LaravelBlogAdmin\Http\Controllers;

use Bjuppa\LaravelBlogAdmin\Http\Requests\StoreBlogEntry;
use Bjuppa\LaravelBlogAdmin\Http\Requests\UpdateBlogEntry;
use Bjuppa\LaravelBlog\Contracts\Blog;
use Bjuppa\LaravelBlog\Contracts\BlogRegistry;
use Bjuppa\LaravelBlog\Eloquent\BlogEntry;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Kontenta\Kontour\AdminLink;
use Kontenta\Kontour\Concerns\AuthorizesAdminRequests;
use Kontenta\Kontour\Concerns\RegistersAdminWidgets;
use Kontenta\Kontour\Contracts\CrumbtrailWidget;
use Kontenta\Kontour\Contracts\ItemHistoryWidget;
use Kontenta\Kontour\Contracts\MessageWidget;

//TODO: rename EntryController to BlogEntryController
class EntryController extends BaseController
{
    use RegistersAdminWidgets, AuthorizesAdminRequests;

    protected $blogRegistry;

    public function __construct(BlogRegistry $blogRegistry)
    {
        $this->blogRegistry = $blogRegistry;

        $this->crumbtrail = $this->findOrRegisterAdminWidget(CrumbtrailWidget::class, 'kontourToolHeader');
        $this->messages = $this->findOrRegisterAdminWidget(MessageWidget::class, 'kontourToolHeader');
    }

    public function create($blog_id)
    {
        $blog = $this->blogRegistry->get($blog_id);
        abort_unless($blog, 404, 'Blog "' . e($blog_id) . '" does not exist');

        $entry = $blog->getEntryProvider()->getBlogEntryModel();

        $this->authorizeEditAdminVisit(
            $blog->getCreateAbility(),
            'New ' . $blog->getTitle() . ' entry',
            $blog->getTitle() . ': New entry',
            $blog->getId()
        );

        $this->buildCrumbtrail($blog, 'New entry');
        $this->addMessagesFromSession();

        return view('blog-admin::entry.create', compact('entry', 'blog'));
    }

    public function store(StoreBlogEntry $request)
    {
        //TODO: make sure this creates an instance of the Blog's entry provider's model
        $entry = BlogEntry::create($request->validatedForModel());

        return redirect(route('blog-admin.entries.edit', [$entry->getBlogId(), $entry->getKey()]))
            ->with('status', 'Blog entry created');
    }

    public function edit($blogId, $id)
    {
        //TODO: make sure this finds an instance via the Blog's entry provider's model
        $entry = BlogEntry::withUnpublished()->findOrFail($id);
        if ($entry->getBlogId() != $blogId) {
            return redirect(route('blog-admin.entries.edit', [$entry->getBlogId(), $entry->getKey()]));
        }

        $blog = $this->blogRegistry->get($entry->getBlogId());

        $this->authorizeEditAdminVisit(
            $blog->getEditAbility(),
            $entry->getTitle(),
            $blog->getTitle() . ': ' . $entry->getTitle(),
            $entry
        );

        $this->buildCrumbtrail($blog, $entry->getTitle());
        $this->addMessagesFromSession();

        $itemHistoryWidget = $this->findOrRegisterAdminWidget(ItemHistoryWidget::class, 'kontourToolWidgets');
        $itemHistoryWidget->addCreatedEntry($entry->getAttribute($entry->getCreatedAtColumn()));
        $itemHistoryWidget->addUpdatedEntry($entry->getAttribute($entry->getUpdatedAtColumn()));

        $blog_options = $this->blogRegistry->all()->filter(function ($blog) {
            return Auth::user()->can($blog->getCreateAbility(), $blog->getId())
            and $blog->getEntryProvider() instanceof \Bjuppa\LaravelBlog\Eloquent\BlogEntryProvider;
        })->mapWithKeys(function ($blog) {
            return [$blog->getId() => $blog->getTitle()];
        })->all();

        return view('blog-admin::entry.edit', compact('entry', 'blog', 'blog_options'));
    }

    public function update(UpdateBlogEntry $request, $id)
    {
        //TODO: make sure this finds an instance of the Blog's entry provider's model
        $entry = BlogEntry::withUnpublished()->findOrFail($id);

        $entry->update($request->validatedForModel());

        return redirect(route('blog-admin.entries.edit', [$entry->getBlogId(), $entry->getKey()]))
            ->with('status', 'Save successful');
    }

    public function destroy($id)
    {
        //TODO: make sure this finds an instance of the Blog's entry provider's model
        $entry = BlogEntry::withUnpublished()->findOrFail($id);
        $blog = $this->blogRegistry->get($entry->getBlogId());

        if ($blog->getEditAbility()) {
            $this->authorize($blog->getEditAbility(), $entry);
        }

        $entry->delete();

        return redirect(route('blog-admin.blogs.show', $entry->getBlogId()))->with('status', 'Deleted entry ' . $entry->getTitle());
    }

    protected function buildCrumbtrail(Blog $blog, $currentPageName)
    {
        $this->crumbtrail->addLink(AdminLink::create($blog->getTitle(), route('blog-admin.blogs.show', $blog->getId())));
        $this->crumbtrail->addLink(AdminLink::create($currentPageName, url()->current()));
    }

    protected function addMessagesFromSession() {
        $this->messages->addMessageIfSessionHasErrors('Validation failed, please correct the form input');
        $this->messages->addFromSession();
    }
}
