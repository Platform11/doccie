<?php

namespace App\Http\Requests\Administration;

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
        $administration = Administration::find($this->request->get('administration_id'));
        if(empty($administration))
        {
            return false;
        }
        return Auth::user()->account->id == $administration->account->id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
             'administration_id' => 'required|exists:administrations,id',
             'name' => 'required',
             'code' => 'required|numeric',
             'call_posts_code' => 'numeric|required',
             'creditors_code' => 'numeric',
             'debtors_code' => 'numeric',
        ];
    }
}
