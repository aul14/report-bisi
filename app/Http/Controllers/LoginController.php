<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function index()
    {
        return view('auth.index');
    }
    public function login(LoginRequest $request)
    {
        $credentials = $request->getCredentials();

        if (!Auth::validate($credentials)) :
            return redirect()->to('login')
                ->withErrors(trans('auth.failed'));
        endif;

        $user = Auth::getProvider()->retrieveByCredentials($credentials);
      
        Session::put('login_id', $user->idUsers);
        Session::put('username', $user->UserName);

        // Auth::setUser($user);
        Auth::loginUsingId($user->idUsers);
        
        return $this->authenticated($request, $user);
    }

    protected function authenticated(Request $request, $user)
    {
        return redirect('/');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        Session::flush();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->back();
    }
}
