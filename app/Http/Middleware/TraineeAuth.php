<?php

namespace App\Http\Middleware;

use Closure;

class TraineeAuth
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
        $logined_trainee = $request->session()->get('logined_trainee', null);
        if (is_null($logined_trainee)) {
            return $request->ajax() ? response('', 401)->json(['message'=> 'Trainee not logined']) : redirect('/');
        } else {
            return $next($request);
        }
    }
}
