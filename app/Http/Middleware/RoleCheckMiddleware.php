<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleCheckMiddleware
{
    /**
     * Handle an incoming request.
     * https://codeanddeploy.com/blog/laravel/laravel-8-user-roles-and-permissions-step-by-step-tutorial
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, string $role)
    {
        $user = auth()->guard('api')->user();
        if (!$user->role->name == 'superadmin') {
            $roles = explode('|', $role);

            foreach ($roles as $role) {
                if ($user->role->name == $role) {
                    return $next($request);
                }
            }

            return Response()->json(['message' => 'You are not authorized to access this resource.'], 403);
        }
        
        return $next($request);
    }
}
