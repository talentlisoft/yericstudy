<?php

namespace App\Http\Middleware;

use Closure;

class TraineeGuest
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
            return $next($request);
        } else {
            return redirect('/trainee');
        }
    }
    
}
