<?php

namespace App\Http\Requests\Admin\Course;

use Illuminate\Foundation\Http\FormRequest;
use JetBrains\PhpStorm\ArrayShape;

class UpdateRequest extends FormRequest
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
    #[ArrayShape(['id' => 'integer', 'title' => "string", 'online_status' => "string"])]
    public function rules(): array
    {
        return [
            'id' => 'required',
            'title' => 'nullable|integer',
            'online_status' => 'integer',
        ];
    }
}
