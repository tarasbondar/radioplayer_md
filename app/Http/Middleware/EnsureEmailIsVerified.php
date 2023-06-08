<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmailIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', $request->only('email'))->first();
        if ($user && !$user->hasVerifiedEmail()) {
            return back()->withInput($request->only('email'))
                ->with('status', __('auth.verify'))
                ->withErrors(['email' => __('auth.verify')]);
        }

        return $next($request);
    }
}
