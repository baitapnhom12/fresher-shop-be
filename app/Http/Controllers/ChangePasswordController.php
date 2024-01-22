<?php

namespace App\Http\Controllers;

use App\Http\Requests\Users\ChangePasswordRequest;
use App\Services\UserService;

class ChangePasswordController extends Controller
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function sendChangePassword(ChangePasswordRequest $request)
    {
        return $this->userService->changePassword($request);
    }
}
