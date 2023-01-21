<?php

namespace App\Http\Requests\Admin\Subject;

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
    #[ArrayShape(['id' => "string", 'name' => "string", 'icon_url' => "string", 'channel_id' => "string", 'online_status' => "string"])]
    public function rules(): array
    {
        return [
            'id' => 'required|integer',
            'name' => 'nullable|string',
            'icon_url' => 'nullable|string',
            'channel_id' => 'nullable|integer',
            'online_status' => 'nullable|integer',
        ];
    }
}
