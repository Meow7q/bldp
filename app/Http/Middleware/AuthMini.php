<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

class AuthMini
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
        if (! $this->auth->parser()->setRequest($request)->hasToken()) {
            return $this->fail('Unauthorized!！', 401);
        }
        try {
            $user = $this->auth->user();
            if(!$user){
                return $this->fail('Unauthorized!！', 401);
            }
            $request->mini_user = $user;
        } catch (TokenExpiredException $e) {
            return $this->fail('Unauthorized!！', 401);
        } catch (JWTException $e) {
            return $this->fail('Unauthorized!！', 401);
        }

        return $next($request);
    }
}
