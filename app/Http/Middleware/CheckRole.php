<?php

namespace App\Http\Middleware;

use Closure;

class CheckRole
{
    private $roleHirarchy = array(
        'ROLE_CRUD' => array('ROLE_READONLY')
    );
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        // Check if a role is required for the route, and
        // if so, ensure that the user has that role.
        //ROLEHIRARCHY TOCHANGE
        if($request->user()->role == $role ||
            in_array($request->user()->role, array_keys($this->roleHirarchy)) || !$role)
        {
            return $next($request);
        }
        return response([
            'error' => [
                'code' => 'INSUFFICIENT_ROLE',
                'description' => 'You are not authorized to access this resource.'
            ]
        ], 401);
    }
}