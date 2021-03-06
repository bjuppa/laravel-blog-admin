<?php

namespace Bjuppa\LaravelBlogAdmin\Tests\Feature;

use Bjuppa\LaravelBlogAdmin\Tests\Feature\Fakes\User;
use Bjuppa\LaravelBlogAdmin\Tests\IntegrationTest;
use Bjuppa\LaravelBlog\Eloquent\BlogEntry;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Carbon;

class BlogAdminTest extends IntegrationTest
{
    /**
     * @var User
     */
    private $user;

    public function setUp(): void
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

        $response->assertSee('>Main Blog</a>', false);
    }

    public function test_show_blog_page()
    {
        $entry = factory(BlogEntry::class)->create();
        $response = $this->actingAs($this->user)->get(route('blog-admin.blogs.show', 'main'));

        $response->assertStatus(200);
        $response->assertSee('>Main Blog</h1>', false);
        $response->assertSee('>' . $entry->getTitle() . '</a>', false);
        $response->assertSee('href="' . route('blog-admin.entries.create', 'main') . '"', false);
    }

    public function test_create_entry_page()
    {
        $response = $this->actingAs($this->user)->get(route('blog-admin.entries.create', 'main'));

        $response->assertStatus(200);
        $response->assertSee('value="main"', false);
    }

    public function test_entry_can_be_stored()
    {
        $formData = [
            'blog' => 'main',
            'title' => 'A new blog post',
            'content' => 'It’s so pointless we just call it content.',
        ];
        $response = $this->actingAs($this->user)->post(route('blog-admin.entries.store'), $formData);

        $response->assertRedirect(route('blog-admin.entries.edit', ['main', 1]));
    }

    public function test_edit_entry_page()
    {
        $entry = factory(BlogEntry::class)->create();
        $entry->refresh();

        $response = $this->actingAs($this->user)->get(route('blog-admin.entries.edit', [$entry->getBlogId(), $entry->getKey()]));

        $response->assertStatus(200);
        $response->assertSee('value="PATCH"', false);
    }

    public function test_entry_can_be_updated()
    {
        $entry = factory(BlogEntry::class)->create();
        $entry->refresh();

        $formData = [
            'title' => 'Replaced title',
            'blog' => $entry->blog,
            'publish_after' => 'tomorrow noon',
        ];
        $response = $this->actingAs($this->user)->patch(route('blog-admin.entries.update', $entry->getKey()), $formData);

        $response->assertRedirect(route('blog-admin.entries.edit', [$entry->getBlogId(), $entry->getKey()]));
        $entry->refresh();
        $this->assertEquals('Replaced title', $entry->title);
        $this->assertEquals(new Carbon('tomorrow noon'), $entry->publish_after, 'Time was not parsed properly from string');
    }

    public function test_entry_cant_be_moved_to_non_existing_blog()
    {
        $entry = factory(BlogEntry::class)->create();
        $entry->refresh();

        $formData = [
            'blog' => 'nonexisting',
        ];
        $response = $this->actingAs($this->user)->patch(route('blog-admin.entries.update', $entry->getKey()), $formData);

        $response->assertStatus(404);
    }

    public function test_entry_can_be_deleted()
    {
        $entry = factory(BlogEntry::class)->create();
        $entry->refresh();

        $response = $this->actingAs($this->user)->delete(route('blog-admin.entries.destroy', [$entry->getBlogId(), $entry->getKey()]));

        $response->assertRedirect(route('blog-admin.blogs.show', [$entry->getBlogId()]));

        $this->assertDatabaseMissing('blog_entries', ['id' => $entry->getKey()]);
    }
}
