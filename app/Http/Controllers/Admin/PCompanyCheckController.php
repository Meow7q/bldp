<?php


namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\PCompanyCheck\TableList;
use App\Services\Admin\CheckService;
use App\Services\Admin\PCompanyDatastaticsService;
use Illuminate\Http\Request;

class PCompanyCheckController extends Controller
{
    protected $service;

    protected $data_service;

    public function __construct(CheckService $service, PCompanyDatastaticsService $data_service)
    {
        $this->service = $service;

        $this->data_service = $data_service;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Box\Spout\Common\Exception\IOException
     * @throws \Box\Spout\Common\Exception\UnsupportedTypeException
     * @throws \Box\Spout\Reader\Exception\ReaderNotOpenedException
     * @throws \Illuminate\Validation\ValidationException
     */
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

        try{
            $this->service->importExcel($path, $validated['table_name'],$file->getClientOriginalName());
        }catch (\Exception $e){
//            throw $e;
            return $this->fail('模版错误');
        }
        return $this->message('ok');
    }

    /**
     * 数据展示
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function show(Request $request){
        try{
            $validated = $this->validate($request, ['table_name' => 'required']);
            $list = $this->service->show($validated['table_name']);
            return $this->success($list);
        }catch (\Exception $e){
            return $this->success([]);
        }
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function statisticsData(){
        $data = $this->data_service->getDataStatistics();
        return $this->success($data);
    }

    /**
     * 更新数据
     * @param Request $request
     * @throws \Illuminate\Validation\ValidationException
     */
    public function updateText(Request $request){
        $validated = $this->validate($request, ['text_arr' => 'required']);
        $this->data_service->updateText($validated['text_arr']);
        return $this->message('ok');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function getFileList(Request $request){
        $validated = $this->validate($request, ['table_name' => 'required']);
        $data = TableList::where('table_name', $validated['table_name'])
            ->select(['id', 'table_name', 'file_name', 'file_path', 'created_at'])
            ->orderBy('created_at', 'desc')
            ->simplePaginate($request->per_page??10)->toArray();
        return $this->success($data);
    }
}
