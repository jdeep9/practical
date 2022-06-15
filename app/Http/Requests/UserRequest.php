<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            //
            'first_name' => 'required|max:10',
            'last_name' => 'required|max:10',
            'phone' => 'required|numeric',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|regex:/^(?=.*\d)(?=.*[@#\-_$%^&+=§!\?])(?=.*[a-z])(?=.*[A-Z])[0-9A-Za-z@#\-_$%^&+=§!\?]{8,40}$/',
            'image' => 'max:1024',
            'status' => 'required',
        ];
    }
}
