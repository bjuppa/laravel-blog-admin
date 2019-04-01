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
        //TODO: make sure this finds an instance of the Blog's entry provider's model
        $entry = BlogEntry::withUnpublished()->find($this->route('id'));
        $blog = $this->blogRegistry->get($entry->getBlogId());

        return $this->user()->can($blog->getEditAbility(), $entry);
    }
}
