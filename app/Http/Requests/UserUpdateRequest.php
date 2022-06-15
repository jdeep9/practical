<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class UserUpdateRequest extends FormRequest
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
    public function rules(Request $request)
    {
        if(!empty(Request::get('userId'))){
            $userId = Request::get('userId');
        }
        // dd($userId);
        return [
            'first_name' => 'required|max:10',
            'last_name' => 'required|max:10',
            'phone' => 'required|numeric',
            'email' => 'required|email|unique:users,email,'.$userId,
            'image' => 'max:1024',
            'status' => 'required',
        ];
    }
}
