<?php
// app/Http/Controllers/Auth/ActivationController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ActivationNResetToken;

class ActivationController extends Controller
{
    public function activate($token)
    {
        $activationToken = ActivationNResetToken::where('token', $token)
            ->where('type', 'activation')
            ->where('expires_at', '>', now())
            ->first();

        if (!$activationToken) {
            return $this->errorResponse('Invalid or expired activation token', 400);
        }

        $user = $activationToken->user;
        $user->email_verified_at = now();
        $user->save();

        $activationToken->delete();

        return $this->successResponse(null, 'Account activated successfully', 200);
    }
}
