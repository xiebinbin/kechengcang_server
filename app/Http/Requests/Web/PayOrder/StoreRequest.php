<?php

namespace App\Http\Requests\Web\PayOrder;

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
    #[ArrayShape(['title' => "string", 'fee' => "string", 'remark' => "string", 'currency' => "string", 'callback_url' => "string", 'redirect_url' => "string"])] public function rules(): array
    {
        return [
            'title' => 'required|string',
            'fee' => 'required|integer',
            'remark' => 'nullable|string',
            'currency' => 'required|integer',
            'callback_url' => 'nullable|string',
            'redirect_url' => 'nullable|string'
        ];
    }
}
