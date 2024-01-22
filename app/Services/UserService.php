<?php

namespace App\Services;

use App\Enums\ImageDefine;
use App\Http\Resources\AuthResource;
use App\Http\Resources\SuccessResource;
use App\Http\Resources\Users\UserResource;
use App\Models\Image;
use App\Models\User;
use App\Notifications\ResetPassNotification;
use App\Traits\ImageTrait;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserService
{
    use ImageTrait;

    protected $userModel;

    public function __construct(User $userModel)
    {
        $this->userModel = $userModel;
    }

    public function findOneByCondition($condition = [], $relation = [], $selection = ['*'])
    {
        $query = $this->userModel->newQuery();

        if (!empty($condition)) {
            foreach ($condition as $field => $value) {
                $query->where($field, $value);
            }
        }

        if (!empty($relation)) {
            $query->with($relation);
        }

        if (!empty($selection)) {
            $query->select($selection);
        }

        return $query->first();
    }

    public function findByCondition($condition = [], $relation = [], $selection = ['*'], $orderBy = [])
    {
        $query = $this->userModel->newQuery();

        if (!empty($condition)) {
            foreach ($condition as $field => $value) {
                $query->where($field, $value);
            }
        }

        if (!empty($relation)) {
            $query->with($relation);
        }

        if (!empty($selection)) {
            $query->select($selection);
        }

        if (!empty($orderBy)) {
            foreach ($orderBy as $column => $direction) {
                $query->orderBy($column, $direction);
            }
        }

        return $query->first();
    }

    public function findId($id)
    {
        return User::find($id);
    }

    public function login(Request $request)
    {
        try {
            if (!Auth::attempt($request->only('email', 'password'))) {
                return response()->json(['error' => 'error', 'message' => 'email or password is invalid'], 400);
            }
            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                throw ValidationException::withMessages([
                    'email' => ['The provided credentials are incorrect.'],
                ]);
            }

            return new AuthResource($user);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => $e->getCode(),
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function logout()
    {
        try {
            $user = auth()->guard('api')->user();
            if (method_exists($user->currentAccessToken(), 'delete')) {
                $user->currentAccessToken()->delete();

                return new SuccessResource('Logout success');
            }

            auth()->guard('web')->logout();

            return new SuccessResource('Logout success');
        } catch (\Throwable $e) {
            return response()->json([
                'error' => $e->getCode(),
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function register($request): AuthResource
    {
        try {
            $user = $this->userModel->create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            return new AuthResource($user);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => $e->getCode(),
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function editProfile($request)
    {
        try {
            DB::beginTransaction();
            $user = auth()->user();
            if (!$user) {
                return response()->json(['message' => 'Not found'], 404);
            }

            $images = $request->file('images');

            if (!empty($images)) {
                $path = 'users/';
                $imageData = [];

                foreach ($images as $image) {
                    $fileData = $this->uploads($image, $path);
                    $imageData[] = [
                        'path' => $fileData['filePath'],
                        'main' => ImageDefine::ImageNotMain,
                    ];
                }
                $user->images()->createMany($imageData);
            }

            $imageUpdate = (int) $request->imageUpdate;
            $imageDelete = $request->imageDelete;
            if ($imageUpdate) {
                Image::where('user_id', auth()->user()->id)->update(['main' => ImageDefine::ImageNotMain]);
                Image::where('id', $imageUpdate)->update(['main' => ImageDefine::ImageMain]);
            }

            if (!empty($imageDelete)) {
                $imageDel = Image::where('user_id', auth()->user()->id)->whereIn('id', $imageDelete)->select('id', 'path')->get();
                $imageDelPath = $imageDel->pluck('path')->toArray();
                $this->deleteFile($imageDelPath);
                Image::whereIn('id', $imageDelete)->delete();
            }

            $result = $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'birthday' => $request->birthday,
                'phone' => $request->phone,
            ]);
            DB::commit();

            return $result;
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'error' => $e->getCode(),
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function changePassword($request)
    {
        try {
            $user = auth()->user();
            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json('Password is invalid', 400);
            }

            $user->update(['password' => Hash::make($request->new_password)]);

            return response()->json('Change password successfully', 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getCode(),
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function getProfile()
    {
        try {
            $user = $this->findOneByCondition(
                ['users.id' => auth()->user()->id],
                ['addresses', 'paymentMethods', 'images'],
                [
                    'id',
                    'name',
                    'email',
                    'birthday',
                    'phone',
                    'role',
                ]
            );

            $data = [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
                'birthday' => $user['birthday'],
                'phone' => $user['phone'],
                'image' => collect($user->images)->map(fn ($image) => [
                    'id' => $image->id,
                    'imagePath' => $image->path,
                    'main' => $image->main ? true : false,
                ]),
                'role' => $user['role'],
                'address' => collect($user->addresses)->map(function ($item) {
                    return [
                        'id' => $item['id'],
                        'user_id' => $item['user_id'],
                        'receiver' => $item['receiver'],
                        'phone' => $item['phone'],
                        'address' => $item['address'],
                    ];
                })->toArray(),
                'paymentMethod' => collect($user->paymentMethods)->map(function ($item) {
                    return [
                        'id' => $item['id'],
                        'user_id' => $item['user_id'],
                        'type' => $item['type'],
                        'provider' => $item['provider'],
                        'accountNumber' => $item['account_number'],
                    ];
                })->toArray(),
            ];

            return new UserResource($data);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => $e->getCode(),
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function forgetPassword(Request $request)
    {
        $this->userModel->where('email', $request->email)->first()->notify(new ResetPassNotification);
    }

    public function resetPassword(Request $request)
    {
        $user = $this->userModel->where('email', $request->email)->first();
        $user->update([
            'password' => Hash::make($request->password),
        ]);
        DB::table('otps')->whereIn('valid', ['0'])->delete();
    }

    public function updateProfile(Request $request)
    {
        try {
            $id = $request->id;
            DB::beginTransaction();
            $profile = $this->findId($id);
            if (!$profile) {
                throw new \Exception('profile not found', 404);
            }

            $profileUpdate = $profile->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);

            $images = $request->file('images');

            if (!empty($images)) {
                $path = 'profiles/';

                foreach ($images as $image) {
                    $fileData = $this->uploads($image, $path);
                    $imageData[] = [
                        'path' => $fileData['filePath'],
                        'main' => 0,
                    ];
                }
                $imageCreate = $profile->images()->createMany($imageData);
            }

            $imageUpdate = (int) $request->imageUpdate;
            $imageDelete = json_decode($request->imageDelete);
            if ($imageUpdate) {
                Image::where('user_id', $id)->update(['main' => 0]);
                Image::where('id', $imageUpdate)->update(['main' => 1]);
            }

            if (!empty($imageDelete)) {
                $imageDel = Image::whereIn('user_id', $imageDelete)->select('id', 'path')->get();
                $imageDelPath = $imageDel->pluck('path')->toArray();
                $this->deleteFile($imageDelPath);
                Image::whereIn('id', $imageDelete)->delete();
            }

            DB::commit();

            if ($profileUpdate || ($profileUpdate && $imageCreate)) {
                return true;
            }

            return false;
        } catch (\Throwable $e) {
            DB::rollBack();

            return false;
        }
    }
}
