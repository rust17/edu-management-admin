<?php

namespace App\Admin\Middleware;

use Closure;
use Illuminate\Http\Request;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Auth\Database\Administrator;

class CheckAdminRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        /** @var Administrator $user */
        $user = Admin::user();

        if (!$user || !array_intersect($roles, explode(',', $user->role))) {
            return redirect(admin_url('/'));
        }

        return $next($request);
    }
}
