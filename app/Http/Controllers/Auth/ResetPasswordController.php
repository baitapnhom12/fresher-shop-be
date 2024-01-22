<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\ResetPasswordRequest;
use App\Services\UserService;
use Ichtrojan\Otp\Otp;
use Illuminate\Http\Request;

class ResetPasswordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    private $otp;

    private $userService;

    public function __construct(UserService $userService)
    {
        $this->otp = new Otp;
        $this->userService = $userService;
    }

    public function resetPassword(Request $request)
    {
        $email = $request->email;

        return view('auth.reset', compact('email'));
    }

    public function sendResetPassword(ResetPasswordRequest $request)
    {
        $otp = $this->otp->validate($request->email, $request->otp);
        if (!$otp->status) {
            return back()->with('otp', 'OTP does not exist');
        } else {
            try {
                $this->userService->resetPassword($request);

                return redirect()->route('login')->with('success', 'Reset Password');
            } catch (\Exception $e) {
                return back()->with('error', 'Reset Password Error');
            }
        }
    }
}
