<?php

namespace App\Http\Requests\Settings;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\BelongsToAccount;
use Illuminate\Support\Facades\Auth;
use App\Models\Administration;

class UpdateInfoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {   
        //Check if user belongs to same account as the adminstration that is being updated.
        return Auth::user()->account->id == $this->request->get('account_id');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'account_id' => 'required|exists:accounts,id',
            'name' => 'required',
            'twinfield_office_code' => 'required',
        ];
    }
}
