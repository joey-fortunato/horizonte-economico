<?php

namespace App\Http\Requests;

use App\Enums\ArticleStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class ArticleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->canAccessBackoffice() ?? false;
    }

    public function rules(): array
    {
        $articleId = $this->route('article')?->id;

        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('articles', 'slug')->ignore($articleId)],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'body' => ['nullable', 'string'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'tags' => ['array'],
            'tags.*' => ['integer', 'exists:tags,id'],
            'status' => ['required', new Enum(ArticleStatus::class)],
            'published_at' => ['nullable', 'date'],
            'reading_minutes' => ['nullable', 'integer', 'min:1', 'max:120'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string', 'max:320'],
            'cover' => ['nullable', 'image', 'max:5120'], // 5 MB
            'correction_note' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
