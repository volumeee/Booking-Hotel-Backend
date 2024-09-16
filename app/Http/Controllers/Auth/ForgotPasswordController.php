<?php
// app/Http/Controllers/Auth/ForgotPasswordController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ActivationNResetToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    public function sendResetLinkEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return $this->errorResponse('User not found', 404);
        }

        $token = ActivationNResetToken::create([
            'user_id' => $user->id,
            'token' => Str::random(64),
            'type' => 'password_reset',
            'expires_at' => now()->addHours(1),
        ]);

        // TODO: Implement sending password reset email

        return $this->successResponse(['token' => $token->token], 'Reset link sent to your email', 200);
    }

    public function reset(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 422);
        }

        $resetToken = ActivationNResetToken::where('token', $request->token)
            ->where('type', 'password_reset')
            ->where('expires_at', '>', now())
            ->first();

        if (!$resetToken) {
            return $this->errorResponse('Invalid or expired reset token', 400);
        }

        $user = $resetToken->user;

        if ($user->email !== $request->email) {
            return $this->errorResponse('Email does not match', 400);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        $resetToken->delete();

        return $this->successResponse(null, 'Password reset successfully', 200);
    }
}
