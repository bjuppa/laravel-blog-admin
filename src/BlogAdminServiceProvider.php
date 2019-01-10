<?php

namespace Bjuppa\LaravelBlogAdmin;

use Bjuppa\LaravelBlogAdmin\Policies\BlogEntryPolicy;
use Bjuppa\LaravelBlog\Contracts\Blog;
use Bjuppa\LaravelBlog\Contracts\BlogRegistry;
use Bjuppa\LaravelBlog\Eloquent\BlogEntry;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Kontenta\Kontour\AdminLink;
use Kontenta\Kontour\Concerns\RegistersAdminRoutes;
use Kontenta\Kontour\Contracts\AdminBootManager;
use Kontenta\Kontour\Contracts\MenuWidget;

class BlogAdminServiceProvider extends ServiceProvider
{
    use RegistersAdminRoutes;

    /**
     * Register bindings in the container.
     */
    public function register()
    {
        $this->configure();
    }

    /**
     * Bootstrap any application services.
     *
     */
    public function boot(BlogRegistry $blogRegistry, AdminBootManager $adminBootManager)
    {
        $this->registerAdminRoutes(__DIR__ . '/../routes/blog-admin.php');
        $this->registerResources();

        Gate::define('manage blog', function ($user, string $blogId) {
            return true;
        });
        Gate::policy(BlogEntry::class, BlogEntryPolicy::class);

        $adminBootManager->beforeRoute(function (BlogRegistry $blogRegistry, MenuWidget $menuWidget) {
            $blogRegistry->all()->filter(function ($blog) {
                return $blog->getEntryProvider() instanceof \Bjuppa\LaravelBlog\Eloquent\BlogEntryProvider;
            })->each(function (Blog $blog) use ($menuWidget) {
                $menuWidget->addLink(
                    AdminLink::create($blog->getTitle(), route('blog-admin.blogs.show', $blog->getId()))
                    ->registerAbilityForAuthorization('manage blog', $blog->getId()),
                    'Blogs'
                );
            });
        });
    }

    /**
     * Setup the configuration.
     */
    protected function configure()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/blog-admin.php', 'blog-admin');

        // Ensure default config values are set for those that are used in two or more places
        if (empty(config('blog-admin.view_namespace'))) {
            config(['blog-admin.view_namespace' => 'blog-admin']);
        }
        if (empty(config('blog-admin.trans_namespace'))) {
            config(['blog-admin.trans_namespace' => 'blog-admin']);
        }
    }

    /**
     * Register the resources.
     */
    protected function registerResources()
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', config('blog-admin.view_namespace'));
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', config('blog-admin.trans_namespace'));
    }
}
