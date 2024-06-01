<?php

namespace App\Controllers;

use App\Controllers\BaseController;

use App\Libraries\CIAuth;
use App\Libraries\hash;
use App\Models\User;

class AuthController extends BaseController
{
    protected $helper = ['url', 'form'];

    public function loginForm()
    {
        $data = [
            'pageTitle' => 'Login',
            'validation' => null
        ];
        return view('backend/pages/auth/login', $data);
    }
    public function loginHandler()
    {
        $fieldType = filter_var($this->request->getVar('login_id'), FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        if ($fieldType = 'email') {
            $isValid = $this->validate([
                'login_id' => [
                    'rules' => 'required|valid_email|is_not_unique[user.email]',
                    'errors' => [
                        'required' => 'Email is required',
                        'valid_email' => 'Please check the email field. It does not appears to be valid.',
                        'is_not_unique' => 'Email is not exists in system'
                    ]
                ],
                'password' => [
                    'rules' => 'required|min_length[8]|max_length[45]',
                    'errors' => [
                        'required' => 'Password is required',
                        'min_length' => 'Password must have atleast 8 characters',
                        'max_length' => 'Password must not have more than 45 characters'
                    ]
                ]
            ]);
        } else {
            $isValid = $this->validate([
                'login_id' => [
                    'rules' => 'required|is_not_unique[user.username]',
                    'errors' => [
                        'required' => 'Username is required',
                        'is_not_unique' => 'Username is not exists in system'
                    ]
                ],
                'password' => [
                    'rules' => 'required|min_length[8]|max_length[45]',
                    'errors' => [
                        'required' => 'Password is required',
                        'min_length' => 'Password must have atleast 8 characters',
                        'max_length' => 'Password must not have more than 45 characters'
                    ]
                ]
            ]);
        }
        if (!$isValid) {
            return view('backend/pages/auth/login', [
                'pageTitle' => 'Login',
                'validation' => $this->validator
            ]);
        } else {
            echo 'Form validated...';
        }
    }
}
