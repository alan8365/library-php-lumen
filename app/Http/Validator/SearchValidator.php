<?php

namespace App\Http\Validator;

use Illuminate\Support\Facades\Validator;

class SearchValidator extends Validator
{
    public function rules()
    {
        return [
            'title' => 'required|max:255',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Please input title',
            'title.max' => 'title at most :max characters',
        ];
    }

}
