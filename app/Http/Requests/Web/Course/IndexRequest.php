<?php

namespace App\Http\Requests\Web\Course;

use Illuminate\Foundation\Http\FormRequest;
use JetBrains\PhpStorm\ArrayShape;

class IndexRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return string[]
     */
    public function rules(): array
    {
        return [
            'channel_ids' => 'nullable',
            'subject_ids' => 'nullable',
            'category_ids' => 'nullable',
            'recommend_status' => 'nullable',
            'q' => 'nullable',
            'page' => 'nullable',
            'limit' => 'nullable',
            'sort_key' => 'nullable'
        ];
    }
}
