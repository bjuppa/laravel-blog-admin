<?php

namespace Bjuppa\LaravelBlogAdmin\Http\Requests;

class StoreBlogEntry extends SaveBlogEntry
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $blogId = $this->input('blog');
        return $this->user()->can('manage blog', $blogId);
    }

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
