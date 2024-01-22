<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\ForgetPasswordRequest;
use App\Services\UserService;

class ForgetPasswordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function forgetPassword()
    {
        return view('auth.forget');
    }

    public function sendForgetPassword(ForgetPasswordRequest $request)
    {
        try {
            $this->userService->forgetPassword($request);
            $url = url('reset-password', [$request->email]);

            return redirect($url);
        } catch (\Exception $e) {
            return back()->with('error', 'Send mail fail');
        }
    }
}
