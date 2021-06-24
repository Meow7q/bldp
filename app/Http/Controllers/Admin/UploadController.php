<?php


namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Services\ExcelService;
use Illuminate\Http\Request;

class UploadController extends Controller
{
//
    public function upload(Request $request){
        //上传文件
        if (!$request->hasFile('file')) {
            return $this->fail('请上传文件!');
        }

        //获取文件
        $file = $request->file('file');
        //校验文件
        if (!isset($file) || !$file->isValid()) {
            return self::resJson(1, '上传失败');
        }

        $ext = $file->getClientOriginalExtension(); //上传文件的后缀
        //设置文件后缀白名单
        $allowExt = ['xlsx', 'csv'];
        if (empty($ext) or in_array(strtolower($ext), $allowExt) === false) {
            return $this->fail('不允许的文件类型!');
        }

        $path = $file->storeAs(
            date('Y-m-d', time()),
            md5(bcrypt(time().uniqid())).'.'.$ext,
            'public'
        );
        return $this->success([
            'path' => asset('upload/'.$path),
            'origin_name' => $file->getClientOriginalName()
        ]);
    }

    public function read(){
        (new ExcelService())->import();
    }
}
