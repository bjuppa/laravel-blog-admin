<?php

namespace Bjuppa\LaravelBlogAdmin\Http\Requests;

use Bjuppa\LaravelBlog\Contracts\BlogRegistry;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

abstract class SaveBlogEntry extends FormRequest
{
    public function __construct(BlogRegistry $blogRegistry)
    {
        $this->blogRegistry = $blogRegistry;
    }

    public function prepareForValidation()
    {
        $publish_after = $this->parseInBlogTimezone($this->publish_after);

        if ($publish_after !== false) {
            $this->merge([
                'publish_after' => $publish_after,
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
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
    }

    public function getValidBlogsForCurrentUser()
    {
        return $this->blogRegistry->all()->filter(function ($blog) {
            return $this->user()->can($blog->getCreateAbility(), $blog->getId())
            and $blog->getEntryProvider() instanceof \Bjuppa\LaravelBlog\Eloquent\BlogEntryProvider;
        });
    }

    public function getRequestedBlog()
    {
        return $this->blogRegistry->get($this->input('blog'));
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
            return Carbon::parse($time, $this->getRequestedBlog()->getTimezone());
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
