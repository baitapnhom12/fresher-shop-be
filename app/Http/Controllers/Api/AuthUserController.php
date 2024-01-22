<?php

namespace App\Http\Controllers\Api;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use App\Http\Requests\Users\ChangePasswordRequest;
use App\Http\Requests\Users\EditProfileRequest;
use App\Http\Requests\Users\ForgetPasswordRequest;
use App\Http\Requests\Users\RegisterUserRequest;
use App\Http\Requests\Users\ResetPasswordRequest;
use App\Http\Resources\AuthResource;
use App\Http\Resources\SuccessResource;
use App\Models\User;
use App\Services\UserService;
use Auth;
use Ichtrojan\Otp\Otp;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthUserController extends Controller
{
    private $otp;

    private $userService;

    public function __construct(UserService $userService)
    {
        $this->otp = new Otp;
        $this->userService = $userService;
    }

    public function register(RegisterUserRequest $request): JsonResponse
    {
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            return $user ? response()->json(new AuthResource($user), 201)
                : response()->json(['message' => 'Registered fail'], 400);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => $e->getCode(),
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Login.
     */
    public function login(AuthRequest $request): JsonResponse
    {
        try {
            if (!Auth::attempt($request->only('email', 'password'))) {
                return response()->json(['error' => 'error', 'message' => 'email or password is invalid'], 400);
            }
            $user = Auth::user();

            if ($user->role !== UserRole::User) {
                Auth::logout();

                return response()->json(['error' => 'unauthorized', 'message' => 'Unauthorized access'], 401);
            }

            return response()->json(new AuthResource($user), 200);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => $e->getCode(),
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Logout
     */
    public function logout(): SuccessResource
    {
        try {
            $user = auth()->guard('api')->user();
            if (method_exists($user->currentAccessToken(), 'delete')) {
                $user->currentAccessToken()->delete();

                return new SuccessResource('Logout success');
            }

            auth()->guard('web')->logout();

            return response()->json(new SuccessResource('Logout success'));
        } catch (\Throwable $e) {
            return response()->json([
                'error' => $e->getCode(),
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function list()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    public function profile(): JsonResponse
    {
        try {
            $profile = $this->userService->getProfile();

            if ($profile) {
                return response()->json($profile, 200);
            }

            return response()->json([
                'error' => 'error',
            ], 500);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => $e->getCode(),
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Edit profile.
     */
    public function editProfile(EditProfileRequest $request): JsonResponse
    {
        try {
            $result = $this->userService->editProfile($request);

            if ($result) {
                return response()->json($result, 200);
            }

            return response()->json([
                'error' => 'error',
            ], 500);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => $e->getCode(),
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Change password
     */
    public function changePassword(ChangePasswordRequest $request): JsonResponse
    {
        return $this->userService->changePassword($request);
    }

    public function fogetPassword(ForgetPasswordRequest $request)
    {
        try {
            $this->userService->forgetPassword($request);

            return response()->json([
                'message' => 'Send Mail Password',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getCode(),
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $otp = $this->otp->validate($request->email, $request->otp);
        if (!$otp->status) {
            return response()->json([
                'message' => 'OTP does not exist',
            ], 500);
        } else {
            try {
                $this->userService->resetPassword($request);

                return response()->json([
                    'message' => 'ResetPassword susscess',
                ], 200);
            } catch (\Exception $e) {
                return response()->json([
                    'error' => $e->getCode(),
                    'message' => $e->getMessage(),
                ], 500);
            }
        }
    }
}
