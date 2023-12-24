<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;

class CheckExpiredTenantMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse) $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $tenant = Tenant::current();

        $dueDate = Carbon::make($tenant->due_at);

        $now = Carbon::now();

        if ($now->greaterThan($dueDate)) {
            return redirect()->route('tenant.expired');
        }

        return $next($request);
    }
}
