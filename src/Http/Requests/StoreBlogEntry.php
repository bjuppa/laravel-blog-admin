<?php

namespace Bjuppa\LaravelBlogAdmin\Http\Requests;

class StoreBlogEntry extends SaveBlogEntry
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return array_merge_recursive(parent::rules(), [
            'title' => ['required'],
            'content' => ['required'],
        ]);
    }
}
