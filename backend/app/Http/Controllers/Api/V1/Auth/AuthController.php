<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
    {
        $data = $request->validated();

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'is_active' => true,
        ]);

        $role = Role::where('name', 'customer')->first();
        if ($role) {
            $user->roles()->attach($role->id);
        }

        event(new Registered($user));

        $token = $user->createToken('mobile')->plainTextToken;

        $user->load('roles');

        return response()->json([
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'email_verified_at' => $user->email_verified_at,
                'roles' => $user->roles->pluck('name'),
            ],
            'message' => 'Registration successful. Please check your email to verify your account.',
        ], 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $data = $request->validated();

        $user = User::where('email', $data['email'])->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials',
            ], 401);
        }

        if (! $user->is_active) {
            return response()->json([
                'message' => 'Account is inactive',
            ], 403);
        }

        $user->tokens()->delete();

        $token = $user->createToken('mobile')->plainTextToken;

        $user->load('roles');

        return response()->json([
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'roles' => $user->roles->pluck('name'),
            ],
        ]);
    }

    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json([
                'message' => 'Password reset link has been sent to your email address.',
            ]);
        }

        return response()->json([
            'message' => 'Unable to send password reset link.',
        ], 400);
    }

    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password): void {
                $user->password = Hash::make($password);
                $user->save();

                $user->tokens()->delete();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json([
                'message' => 'Password has been reset successfully.',
            ]);
        }

        return response()->json([
            'message' => 'Unable to reset password. The token may be invalid or expired.',
        ], 400);
    }

    public function me(Request $request): JsonResponse
    {
        $user = $request->user()->load('roles');

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'roles' => $user->roles->pluck('name'),
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out',
        ]);
    }

    public function verifyEmail(Request $request): JsonResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json([
                'message' => 'Email already verified.',
            ]);
        }

        if ($request->hasValidSignature()) {
            if ($request->user()->markEmailAsVerified()) {
                event(new \Illuminate\Auth\Events\Verified($request->user()));
            }

            return response()->json([
                'message' => 'Email verified successfully.',
            ]);
        }

        return response()->json([
            'message' => 'Invalid verification link.',
        ], 400);
    }

    public function resendVerificationEmail(Request $request): JsonResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json([
                'message' => 'Email already verified.',
            ], 400);
        }

        $request->user()->sendEmailVerificationNotification();

        return response()->json([
            'message' => 'Verification link sent to your email address.',
        ]);
    }
}
