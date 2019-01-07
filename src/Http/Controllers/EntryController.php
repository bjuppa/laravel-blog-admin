<?php

namespace Bjuppa\LaravelBlogAdmin\Http\Controllers;

use Bjuppa\LaravelBlogAdmin\Http\Requests\StoreBlogEntry;
use Bjuppa\LaravelBlogAdmin\Http\Requests\UpdateBlogEntry;
use Bjuppa\LaravelBlog\Contracts\Blog;
use Bjuppa\LaravelBlog\Contracts\BlogRegistry;
use Bjuppa\LaravelBlog\Eloquent\BlogEntry;
use Illuminate\Routing\Controller as BaseController;
use Kontenta\Kontour\AdminLink;
use Kontenta\Kontour\Concerns\DispatchesAdminToolEvents;
use Kontenta\Kontour\Concerns\RegistersAdminWidgets;
use Kontenta\Kontour\Contracts\CrumbtrailWidget;
use Kontenta\Kontour\Contracts\MessageWidget;

//TODO: rename EntryController to BlogEntryController
class EntryController extends BaseController
{
    use RegistersAdminWidgets, DispatchesAdminToolEvents;

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

        $entry = new BlogEntry();
        $entry->blog = $blog->getId();

        $this->buildCrumbtrail($blog, 'New entry');

        $this->dispatchEditAdminToolVisitedEvent($blog->getTitle() . ': New entry');

        return view('blog-admin::entry.create', compact('entry', 'blog'));
    }

    public function store(StoreBlogEntry $request)
    {
        $entry = BlogEntry::create($request->validated());

        return redirect(route('blog-admin.entries.edit', $entry->getKey()))->with('status', 'Blog entry created');
    }

    public function edit($id)
    {
        $entry = BlogEntry::withUnpublished()->findOrFail($id);
        $blog = $this->blogRegistry->get($entry->getBlogId());

        $this->buildCrumbtrail($blog, $entry->getTitle());
        $this->messages->addFromSession();

        $blog_options = $this->blogRegistry->all()->filter(function ($blog) {
            return $blog->getEntryProvider() instanceof \Bjuppa\LaravelBlog\Eloquent\BlogEntryProvider;
        })->mapWithKeys(function ($blog) {
            return [$blog->getId() => $blog->getTitle()];
        })->all();

        $this->dispatchEditAdminToolVisitedEvent($blog->getTitle() . ': ' . $entry->getTitle());

        return view('blog-admin::entry.edit', compact('entry', 'blog', 'blog_options'));
    }

    public function update(UpdateBlogEntry $request, $id)
    {
        $entry = BlogEntry::withUnpublished()->findOrFail($id);

        $entry->update($request->validated());

        return redirect(route('blog-admin.entries.edit', $entry->getKey()))->with('status', 'Save successful');
    }

    protected function buildCrumbtrail(Blog $blog, $currentPageName)
    {
        $this->crumbtrail->addLink(AdminLink::create($blog->getTitle(), route('blog-admin.blogs.show', $blog->getId())));
        $this->crumbtrail->addLink(AdminLink::create($currentPageName, url()->current()));
    }
}
