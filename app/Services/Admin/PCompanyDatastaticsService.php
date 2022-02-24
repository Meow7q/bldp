<?php


namespace App\Services\Admin;


use App\Models\PCompanyCheck\Kjbb;
use App\Models\PCompanyCheck\Lnzc;
use App\Models\PCompanyCheck\Zbqk;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use PhpOffice\PhpWord\TemplateProcessor;

class PCompanyDatastaticsService
{
    protected $key = 'p_company_check_data';

    protected $docx;

    public function __construct()
    {
        $this->docx = new TemplateProcessor(public_path('/static/template/点检表.docx'));
    }

    /**
     * 获取首页数据统计
     * @return array|mixed
     */
    public function getDataStatistics(){
        $cace_data = Redis::get($this->key);
        $cace_data = empty($cace_data) ? [] : json_decode($cace_data, true);
        return $cace_data;
    }

    protected function saveDocx($data)
    {
        $cace_data = Redis::get($this->key);
        $cace_data = empty($cace_data) ? [] : json_decode($cace_data, true);
        foreach ($data as $k => $v) {
            $cace_data[$k] = $v;
        }
        $cace_data['current_month'] = Carbon::now()->month;
        Redis::set($this->key, json_encode($cace_data, JSON_UNESCAPED_UNICODE));
        $this->docx->setValues($cace_data);
        $this->docx->saveAs(public_path('/static/template/母公司经营管理指标成果点检表.docx'));
    }


    /**
     * 历年资产详情
     * @return array
     */
    public function statisticsLnzcxq()
    {
        $fee_kg = Lnzc::select(DB::raw("SUM(yxwzc)+SUM(cwfy)+SUM(gz)+SUM(pgzxf)+SUM(zj)+SUM(bgf)+SUM(ywzdf)+SUM(clf)+SUM(qtywcb)+SUM(kgqt) as fee"))
            ->first();
        $fee_ct = Lnzc::select(DB::raw("SUM(ggsjf)+SUM(sds)+SUM(ctqt) as fee"))
            ->first();
        $data = [
            'fee_kg' => $fee_kg->fee,
            'fee_ct' => $fee_ct->fee,
            'fee_total' => $fee_kg->fee + $fee_ct->fee,
        ];
        $this->saveDocx($data);
        return $data;
    }

    /**
     * 资本情况
     * @return array[]
     */
    public function statisticsZbqk()
    {

        //自有资金
        $fee_kg_zyzj = Zbqk::query()->where('type', '控股')->sum('zyzj');
        $fee_ct_zyzj = Zbqk::query()->where('type', '城投')->sum('zyzj');

        //外部融资
        $fee_kg_wbrz = Zbqk::query()->where('type', '控股')->selectRaw('SUM(rzbj)+SUM(rzpjcb) as fee')->first();
        $fee_ct_wbrz = Zbqk::query()->where('type', '城投')->selectRaw('SUM(rzbj)+SUM(rzpjcb) as fee')->first();

        //占用产业资金
        $fee_kg_zycyzj = Zbqk::query()->where('type', '控股')->sum('zycyzj');
        $fee_ct_zycyzj = Zbqk::query()->where('type', '城投')->sum('zycyzj');

        //合计投入
        $fee_kg_hjtr = Zbqk::query()->where('type', '控股')->sum('hjtr');
        $fee_ct_hjtr = Zbqk::query()->where('type', '城投')->sum('hjtr');

        //历年费用支出
        $fee_kg_lnfyzc = Zbqk::query()->where('type', '控股')->sum('lnfyzc');
        $fee_ct_lnfyzc = Zbqk::query()->where('type', '城投')->sum('lnfyzc');

        //形成资产
        $fee_kg_xczc = Zbqk::query()->where('type', '控股')->selectRaw('SUM(zyzj)+SUM(cqgqtz)+SUM(gdzc) as fee')->first();
        $fee_ct_xczc = Zbqk::query()->where('type', '城投')->selectRaw('SUM(rzbj)+SUM(cqgqtz)+SUM(gdzc) as fee')->first();

        $data = [
            'fee_kg_zyzj' => $fee_kg_zyzj,
            'fee_kg_wbrz' => $fee_kg_wbrz->fee,
            'fee_kg_zycyzj' => $fee_kg_zycyzj,
            'fee_kg_hjtr' => $fee_kg_hjtr,
            'fee_kg_lnfyzc' => $fee_kg_lnfyzc,
            'fee_kg_xczc' => $fee_kg_xczc->fee,

            'fee_ct_zyzj' => $fee_ct_zyzj,
            'fee_ct_wbrz' => $fee_ct_wbrz->fee,
            'fee_ct_zycyzj' => $fee_ct_zycyzj,
            'fee_ct_hjtr' => $fee_ct_hjtr,
            'fee_ct_lnfyzc' => $fee_ct_lnfyzc,
            'fee_ct_xczc' => $fee_ct_xczc->fee,
        ];
        $this->saveDocx($data);
        return $data;
    }

    /**
     * 会计报表情况
     */
    public function statisticsKjbbqk(){
        //各项收入
        $fee_kg_gxsr = Kjbb::where('type', '控股')->selectRaw("SUM(yysr)+SUM(tzss)+SUM(qtsy) as fee")->first()->fee;
        $fee_ct_gxsr = Kjbb::where('type', '城投')->selectRaw("SUM(yysr)+SUM(tzss)+SUM(qtsy) as fee")->first()->fee;

        //各项支出
        $fee_kg_gxzc = Kjbb::where('type', '控股')->selectRaw("SUM(yyzc)+SUM(glfy)+SUM(cwfy)+SUM(sjjfj)+SUM(yywjqtzc)+SUM(sds)+SUM(dnjlr)+SUM(qmwfplr) as fee")->first()->fee;
        $fee_ct_gxzc = Kjbb::where('type', '城投')->selectRaw("SUM(yyzc)+SUM(glfy)+SUM(cwfy)+SUM(sjjfj)+SUM(yywjqtzc)+SUM(sds)+SUM(dnjlr)+SUM(qmwfplr) as fee")->first()->fee;

        //会计盈利
        $fee_kg_kjyl = $fee_kg_gxsr - $fee_kg_gxzc;
        $fee_ct_kjyl = $fee_ct_gxsr - $fee_ct_gxzc;

        //管理费用
        $fee_kg_glfy =
        $this->saveDocx();
    }
}
