<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class Ensure2faEnabled
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (config('auth.enable_2fa')) {
            $user = Auth::user();
            if (empty($user->two_factor_secret)) {
                return redirect()->route('profile.show');
            }
        }
        return $next($request);
    }
}
