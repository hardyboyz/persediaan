<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
        if(request()->isMethod('POST')){
            $data = [
                'name' => 'required|unique:categories',
                'image' => 'nullable|mimes:png,jpg,jpeg|max:2048',
                'category_id' => 'required',
                'supplier_id' => 'nullable',
                'description' => 'nullable',
                'unit' => 'required',
            ];
        }elseif(request()->isMethod('PUT')){
            $data = [
                'name' => 'required','unique:categories,name'.$this->id,
                'image' => 'nullable|mimes:png,jpg,jpeg|max:2048',
                'category_id' => 'required',
                'supplier_id' => 'nullable',
                'description' => 'nullable',
                'unit' => 'required',
            ];
        }

        return $data;
    }
}
