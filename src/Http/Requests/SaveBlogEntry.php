<?php

namespace Bjuppa\LaravelBlogAdmin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

abstract class SaveBlogEntry extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // TODO: authorize user against blog
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'blog' => ['filled', 'string'],
            'publish_after' => ['nullable', 'date'],
            'slug' => ['filled', 'alpha_dash', 'max:255'],
            'title' => ['filled', 'string', 'max:255'],
            'author_name' => ['nullable', 'string', 'max:255'],
            'author_email' => ['nullable', 'email', 'max:255'],
            'author_url' => ['nullable', 'url', 'max:255'],
            'image' => ['nullable', 'string'],
            'content' => ['filled', 'string'],
            'summary' => ['nullable', 'string'],
            'page_title' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
            'meta_tags' => ['nullable', 'json'],
            'display_full_content_in_feed' => ['nullable', 'boolean'],
        ];
    }
}
