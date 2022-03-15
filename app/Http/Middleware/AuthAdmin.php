<?php

namespace App\Http\Middleware;

use App\Enum\UserPermission;
use App\Services\ApiResponse;
use Closure;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

class AuthAdmin
{

    use ApiResponse;

    public $auth;

    /**
     * JwtAdminMiddleware constructor.
     */
    public function __construct()
    {
        $this->auth = auth('admin');
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
            return $this->fail('Unauthorized!！', 401);
        }
        try {
            $user = $this->auth->user();
            if(!$user){
                return $this->fail('Unauthorized!！', 401);
            }
            if($request->isMethod('post') && $user->permission == UserPermission::VISITOR){
                return $this->fail('权限不足');
            }
            $request->user = $user;
        } catch (TokenExpiredException $e) {
            return $this->fail('Unauthorized!！', 401);
        } catch (JWTException $e) {
            return $this->fail('Unauthorized!！', 401);
        }

        return $next($request);
    }
}
