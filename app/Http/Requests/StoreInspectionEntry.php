<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInspectionEntry extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {   
        return $this->user()->can('create inspection');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'inspection_id' => 'required|exists:inspections,id',
            'station_id' => 'required|exists:locations,id',
            'checks' => 'required|json',
            'remark' => 'string|nullable',
        ];
    }
}
