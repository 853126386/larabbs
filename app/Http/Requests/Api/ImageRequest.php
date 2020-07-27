<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class ImageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
//    public function authorize()
//    {
//        return false;
//    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules= [
            'type'=>'required|string|in:avatar,topic'
        ];
        if($this->type=='avatar'){
            $rules['image'] = 'required|mimes:jpeg,bmp,png,gif|dimensions:min_width=200,min_height=200';
        }else{
            $rules['image']='required|mimes:jepg,png,jpg,gif';
        }
        return $rules;
    }

    public function messages()
    {

        return [
          'image.dimensions'=>'图片不够清晰'
        ];
    }
}
