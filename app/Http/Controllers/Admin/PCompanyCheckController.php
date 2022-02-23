<?php


namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Services\Admin\CheckService;
use Illuminate\Http\Request;

class PCompanyCheckController extends Controller
{
    protected $service;

    public function __construct(CheckService $service)
    {
        $this->service = $service;
    }

    public function import(Request $request){
        //上传文件
        $validated =  $this->validate($request, ['table_name' => 'required']);
        if (!$request->hasFile('file')) {
            return $this->fail('请上传文件!');
        }
        //获取文件
        $file = $request->file('file');
        //校验文件
        if (!isset($file) || !$file->isValid()) {
            return $this->fail('上传失败');
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
        $this->service->importExcel($path, $validated['table_name']);
        return $this->message('ok');
    }

    /**
     * 数据展示
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function show(Request $request){
        $validated = $this->validate($request, ['table_name' => 'required']);
        $list = $this->service->show($validated['table_name']);
        return $this->success($list);
    }
}
