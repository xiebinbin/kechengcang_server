<?php

namespace App\Http\Requests\Admin\Category;

use Illuminate\Foundation\Http\FormRequest;
use JetBrains\PhpStorm\ArrayShape;

class StoreRequest extends FormRequest
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
    #[ArrayShape(['name' => "string", 'channel_id' => "string", 'subject_id' => "string", 'online_status' => "string"])]
    public function rules(): array
    {
        return [
            'name' => 'required',
            'channel_id' => 'required|integer',
            'subject_id' => 'required|integer',
            'online_status' => 'integer',
        ];
    }
}