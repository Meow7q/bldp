<?php


namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\User;
use EasySwoole\VerifyCode\Conf;
use EasySwoole\VerifyCode\VerifyCode;
use Illuminate\Http\Request;
use Illuminate\Validation\UnauthorizedException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class AuthController extends Controller
{
    public function auth(Request $request){
        $validated = $this->validate($request, ['staffcode' => 'required']);
        $user = User::where('staffcode', $validated['staffcode'])
            ->select(['id', 'staffcode', 'avatar', 'nickname', 'permission'])
            ->first();
        if(!$user){
            throw new UnauthorizedHttpException('未授权！');
        }
        $token = auth('backend')->login($user);
        return $this->success([
            'userinfo' => $user->toArray(),
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('backend')->factory()->getTTL() * 60
        ]);
    }

    /**
     * @param Request $request
     */
    public function createVerifyCode(Request $request){
        $conf = New Conf();
        $conf->setUseCurve();
        $conf->setUseNoise();
        // 设置图片的宽度
        $conf->setImageWidth(400);
        // 设置图片的高度
        $conf->setImageHeight(200);
        // 设置生成字体大小
        $conf->setFontSize(30);
        // 设置生成验证码位数
        $conf->setLength(6);
        $vcode = new VerifyCode($conf);
        $code = $vcode->DrawCode();
        $img_base64 = $code->getImageBase64();
        $code = $code->getImageCode();
    }
}
