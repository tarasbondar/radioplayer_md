<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin extends Middleware
{

    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() &&  auth()->user()->role == User::ROLE_ADMIN) {
            return $next($request);
        }

        return abort(403);

    }
}
