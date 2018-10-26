<?php

namespace Bjuppa\LaravelBlogAdmin\Tests\Feature;

use Bjuppa\LaravelBlogAdmin\Tests\Feature\Fakes\User;
use Bjuppa\LaravelBlogAdmin\Tests\IntegrationTest;
use Bjuppa\LaravelBlog\Eloquent\BlogEntry;
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
        $entry = factory(BlogEntry::class)->create();
        $response = $this->actingAs($this->user)->get(route('blog-admin.blogs.show', 'main'));

        $response->assertStatus(200);
        $response->assertSee('>Main Blog</h1>');
        $response->assertSee('>' . $entry->getTitle() . '</a>');
    }

    public function test_edit_entry_page()
    {
        $entry = factory(BlogEntry::class)->create();
        $response = $this->actingAs($this->user)->get(route('blog-admin.entries.edit', $entry->getKey()));

        $response->assertStatus(200);
        $response->assertSee('value="PUT"');
    }

    public function test_entry_can_be_updated()
    {
        $entry = factory(BlogEntry::class)->create();
        $formData = [

        ];
        $response = $this->actingAs($this->user)->put(route('blog-admin.entries.update', $entry->getKey()), $formData);

        $response->assertRedirect(route('blog-admin.entries.edit', $entry->getKey()));
    }
}
