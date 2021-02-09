<?php

namespace App\Http\Requests\Administration;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\BelongsToAccount;

class StoreRequest extends FormRequest
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
            'name' => 'required',
            'code' => 'required|numeric',
            'call_posts_code' => 'numeric|required',
            'creditors_code' => 'numeric',
            'debtors_code' => 'numeric',
            'contact_first_name' => 'required',
            'contact_last_name' => 'required',
            'contact_email' => 'required|email',
            'relation_manager_id' => ['required', 'exists:users,id', new BelongsToAccount],
        ];
    }
}
