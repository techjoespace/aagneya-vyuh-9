<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\User;

class TimeWindowMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $start_time = env('GAME_START_TIME');
        $end_time = env('GAME_END_TIME');
        $fixed_time_start = Carbon::parse($start_time);
        $fixed_time_end = Carbon::parse($end_time);
        $now = Carbon::now('IST');
        $user_type =  User::where('username', Auth::user()->username)->first()->user_type;
        if($user_type == 'admin') {
            return $next($request);
        }
        if($now >= $fixed_time_start && $now <= $fixed_time_end) {
            return $next($request);
        }
        elseif($now <= $fixed_time_start) {
            return new Response(view('countdown')->with('fixed_time', $fixed_time_start));
        }
        elseif($now >= $fixed_time_end) {
            return new Response(view('end'));
        }
    }
}
