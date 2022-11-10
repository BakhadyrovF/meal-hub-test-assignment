<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PlaySecretSantaFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'user_ids' => ['required', 'array', 'min:2'],
            'user_ids.*' => ['integer', 'exists:users,id']
        ];
    }
}
