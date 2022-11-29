<?php

namespace App\Http\Requests\Admin\Application;

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
    #[ArrayShape(['application_id' => "string", 'admin_user_id' => "string", 'callback_status' => "string", 'pay_status' => "string"])] public function rules(): array
    {
        return [
            'application_id' => 'nullable|integer',
            'admin_user_id' => 'nullable|integer',
            'callback_status' => 'nullable|integer',
            'pay_status' => 'nullable|integer'
        ];
    }
}
