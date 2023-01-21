<?php

namespace App\Http\Requests\Admin\Subject;

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
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    #[ArrayShape(['limit' => "string", 'page' => "string", 'online_status' => "string"])]
    public function rules(): array
    {
        return [
            'limit' => 'required|integer',
            'page' => 'required|integer',
            'online_status' => 'nullable|integer',
        ];
    }
}
