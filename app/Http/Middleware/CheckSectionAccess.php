<?php
namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckSectionAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $permission = null)
    {
        $user = Auth::user();

        if ($user && ($user->hasRole(['admin', 'Super-User']) || $user->hasAnyPermission(['view-all-sections', 'manage-all-sections']))) {
            return $next($request);
        }

        if ($permission && $user instanceof User && $user->hasAnyPermission([$permission])) {
            return $next($request);
        }

        // if no specific permission matches, deny access
        if ($permission) {
            abort(403, 'ليس لديك صلاحية للوصول إلى هذا القسم.');
        }

        return $next($request);
    }
}
