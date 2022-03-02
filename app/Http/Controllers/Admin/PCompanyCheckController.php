<?php


namespace App\Http\Controllers\Admin;


use App\Enum\FinalizeStatus;
use App\Http\Controllers\Controller;
use App\Models\PCompanyCheck\TableList;
use App\Services\Admin\CheckService;
use App\Services\Admin\PCompanyDatastaticsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            $this->service->importExcel('upload/'.$path, $validated['table_name'],$file->getClientOriginalName());
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
        $validated = $this->validate($request, ['table_name' => 'required', 'keyword' => '', 'status' => '']);
        $data = TableList::where('table_name', $validated['table_name'])
            ->where('file_name', 'like', '%'.($validated['keyword']??'').'%')
            ->when(!empty($validated['status']), function ($query) use ($validated){
                $query->where('status', $validated['status']);
            })
            ->select(['id', 'table_name', 'file_name', 'file_path', 'created_at','month', 'status'])
            ->orderBy('created_at', 'desc')
            ->Paginate($request->per_page??10)->toArray();
        return $this->success($data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request){
        $validated = $this->validate($request, ['id' => 'required', 'status' => '', 'month' => '']);
        $status = $validated['status']??null;
        $month = $validated['month']??null;

        if(($status === null) && ($month === null)){
            return $this->fail('参数错误: 是否定稿和定稿月份至少有一个');
        }
        $data = [];
        if($status !== null){
            $data['status'] = $status;
        }
        if($month !== null){
            $data['month'] = $month;
        }
        TableList::where('id', $validated['id'])
            ->update($data);
        return $this->message('ok');
    }

    /**
     * 切换数据源
     * @param Request $request
     * @throws \Box\Spout\Common\Exception\IOException
     * @throws \Box\Spout\Common\Exception\UnsupportedTypeException
     * @throws \Box\Spout\Reader\Exception\ReaderNotOpenedException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function switchDataSourceByMonth(Request $request){
        $validated = $this->validate($request, ['month' => 'required']);
        $table_list = ['lnzc', 'zbqk', 'kjbb', 'srhz', 'fhmx', 'ysbmb', 'ysjlcb', 'dwtzqk', 'xjlbsj', 'xjlbyg'];
        $this->data_service->resetStatisticsData($validated['month']);
        foreach ($table_list as $table){
            $info = TableList::where('table_name', $table)
                ->where('month', $validated['month'])
                ->where('status', FinalizeStatus::YES)
                ->first();
            if($info){
                $this->service->importExcel($info->file_path, $table, null);
            }
        }
        return $this->message('ok');
    }

    /**
     * 首页下载列表
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function getDownloadList(){
        $list = $this->data_service->downlist();
        return $this->success($list);
    }

}
