<?php

namespace Bjuppa\LaravelBlogAdmin\Http\Requests;

use Bjuppa\LaravelBlog\Eloquent\BlogEntry;

class UpdateBlogEntry extends SaveBlogEntry
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $entry = BlogEntry::withUnpublished()->find($this->route('id'));
        $blog = $this->blogRegistry->get($entry->getBlogId());

        return $this->user()->can($blog->getEditAbility(), $entry);
    }
}
