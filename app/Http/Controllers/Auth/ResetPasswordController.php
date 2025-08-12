<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    protected string $redirectTo = RouteServiceProvider::HOME;

    public function showResetForm(Request $request): View
    {
        $token = $request->route()->parameter('token');
        $this->addDescription(
            'Use this form to reset the password for your '.config('app.name').' account.'
        );

        $this->addTitle('Reset Password');
        $this->addViewVariable('token', $token);
        $this->addViewVariable('email', $request->input('email'));

        return $this->view('pages.auth.passwords.reset');
    }

    protected function setUserPassword($user, $password)
    {
        $user->password = $password;
    }
}
