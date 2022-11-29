<?php

namespace App\Http\Requests\Admin\Merchant;

use App\Enums\Fields\Role;
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
        $user = $this->user();
        return $user->role == Role::SUPER;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    #[ArrayShape(['name' => "string", 'password' => "string", 'remark' => "string"])]
    public function rules(): array
    {
        return [
            'name' => 'required',
            'password' => 'required',
            'remark' => 'nullable'
        ];
    }
}
