<?php

namespace App\Admin\Middleware;

use Closure;
use Illuminate\Http\Request;
use Encore\Admin\Facades\Admin;
use App\Models\User;

class CheckAdminRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        /** @var User $user */
        $user = Admin::user();

        if (!$user || !$user->isRoles($roles)) {
            return redirect(admin_url('/'));
        }

        return $next($request);
    }
}
