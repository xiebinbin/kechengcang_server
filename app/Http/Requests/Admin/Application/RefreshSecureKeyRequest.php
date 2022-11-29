<?php

namespace App\Http\Requests\Admin\Application;

use App\Enums\Fields\Role;
use Illuminate\Foundation\Http\FormRequest;
use JetBrains\PhpStorm\ArrayShape;

class RefreshSecureKeyRequest extends FormRequest
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
    #[ArrayShape(['id' => "string"])]
    public function rules(): array
    {
        return [
            'id' => 'required|integer'
        ];
    }
}
