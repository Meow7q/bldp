<?php

namespace App\Http\Middleware;

use App\Services\ApiResponse;
use Closure;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

class JwtCustomAuth
{
    use ApiResponse;

    public $auth;

    /**
     * JwtAdminMiddleware constructor.
     */
    public function __construct()
    {
        $this->auth = auth('backend');
    }

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
            return $this->fail('Unauthorized,token not exist!', 401, 401);
        }
        try {
            $user = $this->auth->user();
            if(!$user){
                return $this->fail('Unauthorized, user not exist!', 401, 401);
            }
            $request->user = $user;
        } catch (TokenExpiredException $e) {
            return $this->fail('Unauthorized, toke expired!', 401, 401);
        } catch (JWTException $e) {
            return $this->fail('Unauthorized！', 401, 401);
        }

        return $next($request);
    }
}
