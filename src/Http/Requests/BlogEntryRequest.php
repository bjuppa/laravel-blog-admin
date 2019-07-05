<?php

namespace Bjuppa\LaravelBlogAdmin\Http\Requests;

use Bjuppa\LaravelBlog\Contracts\Blog;
use Bjuppa\LaravelBlog\Contracts\BlogRegistry;
use Bjuppa\LaravelBlog\Eloquent\AbstractBlogEntry as BlogEntry;
use FewAgency\Carbonator\Carbonator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class BlogEntryRequest extends FormRequest
{
    public function __construct(BlogRegistry $blogRegistry)
    {
        $this->blogRegistry = $blogRegistry;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->isMethod('POST')) {
            return $this->user()->can($this->blog->getCreateAbility(), $this->entry);
        }

        return $this->user()->can($this->blog->getEditAbility(), $this->entry);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'blog' => ['filled', Rule::in($this->getValidBlogsForCurrentUser()->map->getId())],
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
            'json_meta_tags' => ['nullable', 'json'],
            'display_full_content_in_feed' => ['nullable', 'boolean'],
        ];

        if ($this->isMethod('POST')) {
            $rules = array_merge_recursive($rules, [
                'blog' => ['required'],
                'title' => ['required'],
                'content' => ['required'],
            ]);
        }

        return $rules;
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->ensureRequestHasBlogAndEntryInstances();

        if ($publish_after = Carbonator::parseToDefaultTz($this->publish_after, $this->blog->getTimezone())) {
            $this->merge([
                'publish_after' => $publish_after,
            ]);
        }
    }

    public function ensureRequestHasBlogAndEntryInstances()
    {
        abort_unless($this->blog, Response::HTTP_NOT_FOUND, 'No blog specified');

        if (!$this->blog instanceof Blog) {
            $this->blog = $this->blogRegistry->get($this->blog);
        }

        if (!$this->blog) {
            abort(Response::HTTP_NOT_FOUND, 'Blog "' . e($this->blog) . '" does not exist');
        }
        abort_unless($this->blog->getEntryProvider() instanceof \Bjuppa\LaravelBlog\Eloquent\BlogEntryProvider,
            Response::HTTP_INTERNAL_SERVER_ERROR,
            'Blog "' . e($this->blog->getId()) . '" is not configured with the Eloquent entry provider'
        );

        if (!$this->entry instanceof BlogEntry) {
            $this->entry = (
                $this->blog->getEntryProvider()->getBlogEntryModel()->withUnpublished()->find($this->entry) ??
                $this->blog->getEntryProvider()->getBlogEntryModel()
            );
        }

        abort_if($this->isMethod('PATCH') and !$this->entry->exists, Response::HTTP_METHOD_NOT_ALLOWED);
        abort_if($this->isMethod('POST') and $this->entry->exists, Response::HTTP_METHOD_NOT_ALLOWED);
    }

    public function getValidBlogsForCurrentUser()
    {
        return $this->blogRegistry->all()->filter(function ($blog) {
            return $this->user()->can($blog->getCreateAbility(), $blog->getId())
            and $blog->getEntryProvider() instanceof \Bjuppa\LaravelBlog\Eloquent\BlogEntryProvider;
        });
    }
}
