<?php


namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\PCompanyCheck\Kjbb;
use App\Models\PCompanyCheck\Lnzc;
use App\Models\PCompanyCheck\Zbqk;
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

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function statisticsLnzcxq(Request $request){
        $data = $this->data_service->statisticsLnzcxq();
        return $this->success($data);
    }

    /**
     * 资本情况
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function statisticsZbqk(){

        //自有资金
        $fee_kg_zyzj = Zbqk::query()->where('type','控股')->sum('zyzj');
        $fee_ct_zyzj = Zbqk::query()->where('type','城投')->sum('zyzj');

        //外部融资
        $fee_kg_wbrz = Zbqk::query()->where('type','控股')->selectRaw('SUM(rzbj)+SUM(rzpjcb) as fee')->first();
        $fee_ct_wbrz = Zbqk::query()->where('type','城投')->selectRaw('SUM(rzbj)+SUM(rzpjcb) as fee')->first();

        //占用产业资金
        $fee_kg_zycyzj = Zbqk::query()->where('type','控股')->sum('zycyzj');
        $fee_ct_zycyzj = Zbqk::query()->where('type','城投')->sum('zycyzj');

        //合计投入
        $fee_kg_hjtr = Zbqk::query()->where('type','控股')->sum('hjtr');
        $fee_ct_hjtr = Zbqk::query()->where('type','城投')->sum('hjtr');

        //历年费用支出
        $fee_kg_lnfyzc = Zbqk::query()->where('type','控股')->sum('lnfyzc');
        $fee_ct_lnfyzc = Zbqk::query()->where('type','城投')->sum('lnfyzc');

        //形成资产
        $fee_kg_xczc = Zbqk::query()->where('type','控股')->selectRaw('SUM(zyzj)+SUM(cqgqtz)+SUM(gdzc) as fee')->first();
        $fee_ct_xczc = Zbqk::query()->where('type','城投')->selectRaw('SUM(rzbj)+SUM(cqgqtz)+SUM(gdzc) as fee')->first();

        $data = [
            '控股' => [
                'zyzj' => $fee_kg_zyzj,
                'wbrz' => $fee_kg_wbrz->fee,
                'zycyzj' => $fee_kg_zycyzj,
                'hjtr' => $fee_kg_hjtr,
                'lnfyzc' => $fee_kg_lnfyzc,
                'xczc' => $fee_kg_xczc->fee,
            ],
            '城投' => [
                'zyzj' => $fee_ct_zyzj,
                'wbrz' => $fee_ct_wbrz->fee,
                'zycyzj' => $fee_ct_zycyzj,
                'hjtr' => $fee_ct_hjtr,
                'lnfyzc' => $fee_ct_lnfyzc,
                'xczc' => $fee_ct_xczc->fee,
            ]
        ];
        return $this->success($data);
    }

    public function statisticsKjbbqk(){
        //各项收入
        $fee_kg_gxsr = Kjbb::where('type', '控股')->selectRaw("SUM(yysr)+SUM(tzss)+SUM(qtsy) as fee")->first();
        $fee_ct_gxsr = Kjbb::where('type', '城投')->selectRaw("SUM(yysr)+SUM(tzss)+SUM(qtsy) as fee")->first();

        //各项支出
        $fee_kg_gxzc = Kjbb::where('type', '控股')->selectRaw("SUM(yyzc)+SUM(glfy)+SUM(cwfy)+SUM(sjjfj)+SUM(yywjqtzc)+SUM(sds)+SUM(dnjlr)+SUM(qmwfplr) as fee")->first();
        $fee_ct_gxzc = Kjbb::where('type', '城投')->selectRaw("SUM(yyzc)+SUM(glfy)+SUM(cwfy)+SUM(sjjfj)+SUM(yywjqtzc)+SUM(sds)+SUM(dnjlr)+SUM(qmwfplr) as fee")->first();

        //
//        $fee_kg_zycyzj =
//        $fee_ct_zycyzj
    }
}
