<?php

namespace App\Http\Requests\Admin\Subject;

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
    #[ArrayShape(['name' => "string", 'icon_url' => "string", 'channel_id' => "string", 'online_status' => "string"])]
    public function rules(): array
    {
        return [
            'name' => 'required',
            'icon_url' => 'required|string',
            'channel_id' => 'required|integer',
            'online_status' => 'integer',
        ];
    }
}
