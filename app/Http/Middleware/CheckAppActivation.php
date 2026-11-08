<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class CheckAppActivation
{
    public function handle(Request $request, Closure $next)
    {
        // never block the "expired" page itself (avoids a redirect loop)
        if ($request->routeIs('app.expired')) {
            return $next($request);
        }

        // cache an array (not null) so an empty table is cached too
        $info = Cache::remember('app_act_info', 60, function () {
            $actUpto = DB::table('peract_tbl')->orderByDesc('id')->value('act_upto');

            return [
                'has_data' => $actUpto !== null,   // false = table blank (or act_upto is NULL)
                'act_upto' => $actUpto,
            ];
        });

        // table blank -> no need to check
        if (! $info['has_data']) {
            return $next($request);
        }

        if (now()->greaterThan(Carbon::parse($info['act_upto']))) {

            if (Auth::check()) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
            }

            if ($request->header('X-Livewire')) {
                return response('', 409, ['X-Livewire-Redirect' => route('app.expired')]);
            }

            return redirect()->route('app.expired');
        }

        return $next($request);
    }
}