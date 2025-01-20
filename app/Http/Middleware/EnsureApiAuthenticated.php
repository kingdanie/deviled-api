<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Laravel\Sanctum\PersonalAccessToken;

class EnsureApiAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Attempt to authenticate via Sanctum
        $accessToken = $request->bearerToken();
        $token = $accessToken
            ? PersonalAccessToken::findToken($accessToken)
            : null;

        if (!$token || !$token->can($request->route()->getName())) {
            return response()->json([
                'message' => 'Unauthorized',
            ], Response::HTTP_UNAUTHORIZED);
        }

        // Authenticate the user
        $request->setUserResolver(function () use ($token) {
            return $token->tokenable;
        });

        return $next($request);
    }
}
