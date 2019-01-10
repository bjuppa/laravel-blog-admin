<?php

namespace Bjuppa\LaravelBlogAdmin\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Bjuppa\LaravelBlog\Eloquent\BlogEntry;
use Illuminate\Foundation\Auth\User;
use Illuminate\Contracts\Auth\Access\Authorizable;

class BlogEntryPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function edit(Authorizable $user, BlogEntry $entry) {
        return true;
    }
}
