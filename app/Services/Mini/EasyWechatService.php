<?php


namespace App\Services\Mini;


use EasyWeChat\Factory;

class EasyWechatService
{
    protected $app;

    public function __construct()
    {
        $this->app = Factory::miniProgram([
            'app_id' => config('easywechat.app_id'),
            'secret' => config('easywechat.secret'),
            'response_type' => 'array',
            'log' => [
                'level' => 'debug',
                'file' => __DIR__.'/wechat.log',
            ],
        ]);
    }

    public function getOpenId($code){
        return $this->app->auth->session($code);
    }

    public function fail(){}
}
