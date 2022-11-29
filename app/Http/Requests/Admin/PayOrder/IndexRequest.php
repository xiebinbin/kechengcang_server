<?php

namespace App\Http\Requests\Admin\PayOrder;

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
    #[ArrayShape(['page' => "string", 'limit' => "string", 'pay_status' => "string", 'callback_status' => "string", 'status' => "string"])]
    public function rules(): array
    {
        return [
            'page' => 'required|integer',
            'limit' => 'required|integer',
            'status' => 'nullable|integer',
            'callback_status' => 'nullable|integer',
            'pay_status' => 'nullable|integer'
        ];
    }
}
