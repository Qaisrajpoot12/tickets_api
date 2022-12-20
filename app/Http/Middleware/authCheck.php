<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class authCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // dd($request);
        // $login_token = ;
        if (!$request->login_token) {
            $response = [
                'message' => "can't access this page you need to login first",
            ];
            return response()->json($response, 401);
        }else{

            return $next($request);
        }
    
    }
}
