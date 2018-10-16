<?php

namespace Bjuppa\LaravelBlogAdmin;

use Bjuppa\LaravelBlog\Contracts\Blog;
use Bjuppa\LaravelBlog\Contracts\BlogRegistry;
use Illuminate\Support\ServiceProvider;
use Kontenta\Kontour\Concerns\RegistersAdminRoutes;
use Kontenta\Kontour\Concerns\RegistersMenuWidgetLinks;

class BlogAdminServiceProvider extends ServiceProvider
{
    use RegistersAdminRoutes, RegistersMenuWidgetLinks;

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
    public function boot(BlogRegistry $blogRegistry)
    {
        $this->registerAdminRoutes(__DIR__ . '/../routes/blog-admin.php');
        $this->registerResources();
        $blogRegistry->all()->each(function (Blog $blog) {
            $this->addMenuWidgetRoute('blog-admin.blog.show', $blog->getTitle());
        });
    }

    protected function registerMenuLinks()
    {
        //$this->addMenuWidgetRoute();
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
