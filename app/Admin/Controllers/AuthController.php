<?php

namespace App\Admin\Controllers;

use Encore\Admin\Controllers\AuthController as BaseAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends BaseAuthController
{
    public function postLogin(Request $request)
    {
        $credentials = $request->only(['username', 'password']);
        $remember = $request->get('remember', false);

        Validator::make($credentials, [
            'username' => 'required',
            'password' => 'required',
        ])->validate();

        if ($this->guard()->attempt($credentials, $remember)) {
            return $this->sendLoginResponse($request);
        }

        return back()->withInput()->withErrors([
            'username' => $this->getFailedLoginMessage(),
        ]);
    }

    protected function getFailedLoginMessage()
    {
        return trans('admin.auth_failed');
    }
}
