<?php

namespace App\Http\Requests\Invites;

use Illuminate\Foundation\Http\FormRequest;

class AcceptRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'token' => 'required|exists:invites,token',
            'password' => 'min:8|string|required|confirmed',
            'password_confirmation' => 'required',
        ];
    }
}
