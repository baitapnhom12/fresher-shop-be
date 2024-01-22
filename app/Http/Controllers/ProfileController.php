<?php

namespace App\Http\Controllers;

use App\Http\Resources\ErrorResource;
use App\Http\Resources\SuccessResource;
use App\Models\User;
use App\Services\UserService;
use Exception;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function edit(string $id)
    {
        $profile = User::with('images:id,user_id,path')->find($id);

        return view('auth.profile', compact('profile'));
    }

    public function update(Request $request)
    {
        $id = $request->id;
        $request->validate([
            'name' => 'required|max:50',
            'email' => 'required|max:50|unique:users,email,' . $id,
        ]);
        try {
            $this->userService->updateProfile($request, $id);

            return new SuccessResource;
        } catch (Exception $exception) {
            return new ErrorResource;
        }
    }
}
