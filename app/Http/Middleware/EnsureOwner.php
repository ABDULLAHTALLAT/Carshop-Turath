<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/** Car-owner area: signed-in, non-admin accounts only (the broker uses /admin) */
class EnsureOwner
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        abort_unless($user, 403);

        if ($user->is_admin) {
            return redirect()->route('admin.dashboard');
        }

        return $next($request);
    }
}
