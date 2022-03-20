<?php

namespace App\Http\Requests;


class AuthPostRequest extends BaseRequest
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
        return  [
            'username' => ['required'],
            'password' => ['required']
        ];
    }

    public function messages()
    {
        return [
            'username.required' => '账号是必须的',
            'password.required' => '密码是必须的',
        ];
    }
    protected $scene = [
        'login' => ['username','password'],
    ];
}
