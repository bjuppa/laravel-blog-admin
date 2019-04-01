<?php

namespace Bjuppa\LaravelBlogAdmin\Http\Requests;

use Bjuppa\LaravelBlog\Contracts\Blog;
use Bjuppa\LaravelBlog\Contracts\BlogRegistry;
use Bjuppa\LaravelBlog\Eloquent\AbstractBlogEntry as BlogEntry;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveBlogEntry extends FormRequest
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
            return !$this->entry->exists;
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
            'blog' => ['required', Rule::in($this->getValidBlogsForCurrentUser()->map->getId())],
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
        if (!$this->blog instanceof Blog) {
            $this->blog = $this->blogRegistry->get($this->blog);
        }

        if (!$this->blog) {
            abort(404, 'Blog "' . e((string) $this->blog) . '" does not exist');
        }
        abort_unless($this->blog->getEntryProvider() instanceof \Bjuppa\LaravelBlog\Eloquent\BlogEntryProvider,
            500,
            'Blog "' . e($this->blog->getId()) . '" is not configured with the Eloquent entry provider'
        );

        if (!$this->entry instanceof BlogEntry) {
            $this->entry = (
                $this->blog->getEntryProvider()->getBlogEntryModel()->withUnpublished()->find($this->entry) ??
                $this->blog->getEntryProvider()->getBlogEntryModel()
            );
        }

        $publish_after = $this->parseInBlogTimezone($this->publish_after);

        if ($publish_after !== false) {
            $this->merge([
                'publish_after' => $publish_after,
            ]);
        }
    }

    public function getValidBlogsForCurrentUser()
    {
        return $this->blogRegistry->all()->filter(function ($blog) {
            return $this->user()->can($blog->getCreateAbility(), $blog->getId())
            and $blog->getEntryProvider() instanceof \Bjuppa\LaravelBlog\Eloquent\BlogEntryProvider;
        });
    }

    public function parseInBlogTimezone($time)
    {
        if ($time instanceof DateTime) {
            // Any instances of DateTime (e.g. Carbon) can be used with their current timezone
            // We ignore $tz_parse, just as parsing a string including timezone does
            return Carbon::instance($time);
        }

        if (is_array($time)) {
            $time = implode(' ', $time);
        }

        if (!strlen(trim($time))) {
            // Not parsing if "empty", but lets 0 through - it will be interpreted as unix timestamp
            return null;
        }

        try {
            return Carbon::parse($time, $this->blog->getTimezone());
        } catch (\Exception $e) {
            return false;
        }
    }

    public function validatedForModel()
    {
        $validated = $this->validated();

        $publish_after = $this->parseInBlogTimezone($validated['publish_after']);
        if ($publish_after) {
            $validated['publish_after'] = $publish_after->tz(null);
        }

        return $validated;
    }
}
