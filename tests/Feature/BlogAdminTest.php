<?php

namespace Bjuppa\LaravelBlogAdmin\Tests\Feature;

use Bjuppa\LaravelBlogAdmin\Tests\Feature\Fakes\User;
use Bjuppa\LaravelBlogAdmin\Tests\IntegrationTest;
use Illuminate\Support\Facades\Route;

class BlogAdminTest extends IntegrationTest
{
    /**
     * @var User
     */
    private $user;

    public function setUp()
    {
        parent::setUp();
        $this->prepareDatabase();
        $this->user = factory(User::class)->create();
    }

    public function test_blog_has_named_routes()
    {
        $this->assertTrue(Route::has('blog-admin.blogs.show'));
        $this->assertTrue(Route::has('blog-admin.entries.edit'));
    }

    public function test_menu_links()
    {
        $response = $this->actingAs($this->user)->get(route('blog-admin.blogs.show', 'main'));

        $response->assertSee('>Main Blog</a>');
    }

    public function test_show_blog_page()
    {
        $response = $this->actingAs($this->user)->get(route('blog-admin.blogs.show', 'main'));

        $response->assertStatus(200);
        $response->assertSee('>Main Blog</h1>');
    }
}
