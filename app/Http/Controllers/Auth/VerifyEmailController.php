<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke($id, $hash, Request $request): RedirectResponse
    {
        if ($request->hasValidSignature()) {
            $user = User::findOrFail($id);
            if (!$user->hasVerifiedEmail()) {
                $user->markEmailAsVerified();
                event(new Verified($user));
            }

            return redirect('/login')->with('status', 'auth.verified');
        }
        return redirect('/login')->with('error', 'auth.link-expired');
    }
}
