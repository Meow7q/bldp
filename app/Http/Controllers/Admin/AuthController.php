<?php


namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\UnauthorizedException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class AuthController extends Controller
{
    public function auth(Request $request){
        $validated = $this->validate($request, ['staffcode' => 'required']);
        $user = User::where('staffcode', $validated['staffcode'])
            ->select(['staffcode', 'avatar', 'nickname', 'permission'])
            ->first();
        if(!$user){
            throw new UnauthorizedHttpException('未授权！');
        }
        $token = auth('backend')->login($user);
        return $this->success([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('backend')->factory()->getTTL() * 60
        ]);
    }
}
