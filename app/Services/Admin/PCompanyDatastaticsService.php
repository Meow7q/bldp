<?php


namespace App\Services\Admin;


use App\Models\PCompanyCheck\Dwtzqk;
use App\Models\PCompanyCheck\Fhmx;
use App\Models\PCompanyCheck\Kjbb;
use App\Models\PCompanyCheck\Lnzc;
use App\Models\PCompanyCheck\Srhz;
use App\Models\PCompanyCheck\Xjlbsj;
use App\Models\PCompanyCheck\Xjlbyg;
use App\Models\PCompanyCheck\Ysbmb;
use App\Models\PCompanyCheck\Ysjlcb;
use App\Models\PCompanyCheck\Zbqk;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use PhpOffice\PhpWord\TemplateProcessor;

class PCompanyDatastaticsService
{
    public $key = 'p_company_check_data';

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
        $this->docx->setValues($cace_data);
        $file_name = '母公司经营管理指标成果点检表'.uniqid();
        $path = '/static/template/'.$file_name.'.docx';
        $cace_data['current_month'] = Carbon::now()->month;
        $cace_data['docx_path'] = $path;
        Redis::set($this->key, json_encode($cace_data, JSON_UNESCAPED_UNICODE));
        $this->docx->saveAs(public_path($path));
    }


    /**
     * 历年资产详情
     */
    public function statisticsLnzcxq()
    {
        $fee_kg = Lnzc::select(DB::raw("SUM(yxwzc)+SUM(cwfy)+SUM(gz)+SUM(pgzxf)+SUM(zj)+SUM(bgf)+SUM(ywzdf)+SUM(clf)+SUM(qtywcb)+SUM(kgqt) as fee"))
            ->first();
        $fee_ct = Lnzc::select(DB::raw("SUM(ggsjf)+SUM(sds)+SUM(ctqt) as fee"))
            ->first();
        $data = [
            'fee_kg' => $fee_kg->fee/10000,
            'fee_ct' => $fee_ct->fee/10000,
            'fee_total' => bcadd($fee_kg->fee, $fee_ct->fee)/10000,
        ];
        $this->saveDocx($data);
    }

    /**
     * 资本情况
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

        //管理费用(控股)
        $fee_kg = Kjbb::where('type', '控股')->where('year', 2022)->first();
        $fee_kg_glfy = $fee_kg->glfy;
        $fee_kg_dnjlr = $fee_kg->dnjlr;

        //城投
        $fee_ct = Kjbb::where('type', '城投')->where('year', 2022)->first();
        //当年净利润
        $fee_ct_dnjlr = $fee_ct->dnjlr;
        //投资收益
        $fee_ct_tzss = $fee_ct->tzss;
        //财务指标
        $fee_ct_cwfy = $fee_ct->cwfy;
        //会计亏损
        $fee_kjks = $fee_ct_dnjlr + $fee_kg_dnjlr;

        $this->saveDocx([
            'fee_kg_kjyl' => $fee_kg_kjyl,
            'fee_kg_gxsr' => $fee_kg_gxsr,
            'fee_kg_gxzc' => $fee_kg_gxzc,
            'fee_kg_glfy' => $fee_kg_glfy,

            'fee_ct_kjyl' => $fee_ct_kjyl,
            'fee_ct_tzss' => $fee_ct_tzss,
            'fee_ct_cwfy' => $fee_ct_cwfy,
            'fee_kjks' => $fee_kjks,
        ]);
    }

    /**
     * 收入情况
     */
    public function statisticsSrqk(){
        //控股及城投收入合计
        $fee_srqk_srhj = Srhz::selectRaw('SUM(dbfdw)+SUM(zj) as fee')->where('year', 2022)->first()->fee;

        //投资收益
        $fee_srqk_tzss = Srhz::selectRaw('SUM(zj) as fee')->where('year', 2022)->first()->fee;

        //借款利息
        $fee_srqk_jklx = Srhz::selectRaw('SUM(lx) as fee')->where('year', 2022)->first()->fee;

        //担保费
        $fee_srqk_dbf = Srhz::selectRaw('SUM(dbfdw) as fee')->where('year', 2022)->first()->fee;

        //分红
        $fee_srqk_fh = Srhz::selectRaw('SUM(fh) as fee')->where('year', 2022)->first()->fee;

        $this->saveDocx([
            'fee_srqk_srhj' => $fee_srqk_srhj,
            'fee_srqk_tzss' => $fee_srqk_tzss,
            'fee_srqk_jklx' => $fee_srqk_jklx,
            'fee_srqk_dbf' => $fee_srqk_dbf,
            'fee_srqk_fh' => $fee_srqk_fh,
        ]);
    }

    /**
     * 分红情况
     */
    public function statisticsFhqk(){
        $fee_fhqk_kgfh = Fhmx::selectRaw("SUM(fee) as fee")->where('year', 2022)->whereIn('company', ['中南装饰', '中南控股集团（上海）资产管理有限公司'])->first()->fee;
        $fee_fhqk_ctfh = Fhmx::select(['fee'])->where('year', 2022)->where('company', '江苏中南建设集团有限公司')->first()->fee;
        $this->saveDocx([
            'fee_fhqk_kgfh' => $fee_fhqk_kgfh,
            'fee_fhqk_ctfh' => $fee_fhqk_ctfh,
        ]);
    }

    /**
     * 部门预算情况
     */
    public function statisticsBmysqk(){
        //各部门预算数31
        $fee_ysqk_gbmyss = Ysbmb::selectRaw("SUM(fee_e) as fee")->first()->fee;
        //实际发生数32
        $fee_ysqk_sjfss = Ysbmb::selectRaw("SUM(fee_d) as fee")->first()->fee;
        //执行率
        $fee_ysqk_zxl = (round($fee_ysqk_sjfss/$fee_ysqk_gbmyss, 4)*100).'%';

        $this->saveDocx([
            'fee_ysqk_gbmyss' => $fee_ysqk_gbmyss/100000000,
            'fee_ysqk_sjfss' => $fee_ysqk_sjfss/100000000,
            'fee_ysqk_zxl' => $fee_ysqk_zxl,
        ]);
    }

    /**
     *
     * 经理层预算情况
     */
    public function statisticsJlcysqk(){
        //控股经理层预算数34
        $fee_ysqk_kgjlcyss = Ysjlcb::selectRaw("SUM(fee_e) as fee")->first()->fee;
        //经理层实际发生数35
        $fee_ysqk_ylcsjfss = Ysjlcb::selectRaw("SUM(fee_d) as fee")->first()->fee;
        //经理层执行率36
        $fee_ysqk_jlczxl = (round($fee_ysqk_ylcsjfss/$fee_ysqk_kgjlcyss, 4)*100).'%';

        $this->saveDocx([
            'fee_ysqk_kgjlcyss' => $fee_ysqk_kgjlcyss,
            'fee_ysqk_ylcsjfss' => $fee_ysqk_ylcsjfss,
            'fee_ysqk_jlczxl' => $fee_ysqk_jlczxl,
        ]);
    }

    /**
     * 对外投资情况
     */
    public function statisticsDwtzqk(){
        //股权投资37
        $fee_dwtzqk_hjgqtz = Dwtzqk::selectRaw("SUM(gqbj)+SUM(ysgx) as fee")->first()->fee;
        //债权投资38
        $fee_dwtzqk_zqtz = Dwtzqk::selectRaw("SUM(zq)+SUM(yszx) as fee")->first()->fee;
        //投资的留存收益39
        $fee_dwtzqk_lcsy = Dwtzqk::selectRaw("SUM(lcsy) as fee")->first()->fee;
        //合计投入40
        $fee_dwtzqk_hjtr = $fee_dwtzqk_hjgqtz+$fee_dwtzqk_zqtz+$fee_dwtzqk_lcsy;
        $this->saveDocx([
            'fee_dwtzqk_hjgqtz' => $fee_dwtzqk_hjgqtz/10000,
            'fee_dwtzqk_zqtz' => $fee_dwtzqk_zqtz/10000,
            'fee_dwtzqk_lcsy' => $fee_dwtzqk_lcsy/10000,
            'fee_dwtzqk_hjtr' => $fee_dwtzqk_hjtr/10000,
        ]);
    }

    /**
     * 现金流去情况
     */
    public function statisticsXjlqk(){
        //母公司现金流净出41
        $fee_xjlqk_mgsxjljc = Xjlbsj::selectRaw("SUM(fee_kg)+SUM(fee_ct)as fee")->where('name', '!=', '货币资金余额')->first()->fee;
        //经营现金流净出42
        $fee_xjlqk_jyxjljc = Xjlbsj::selectRaw("SUM(fee_kg)+SUM(fee_ct)as fee")->where('name', '经营活动产生的现金流量净额')->first()->fee;
        //融资净流出43
        $fee_xjlqk_rzjlc = Xjlbsj::selectRaw("SUM(fee_kg)+SUM(fee_ct)as fee")->where('name', '融资活动产生的现金流量净额')->first()->fee;
        //投资净流出44
        $fee_xjlqk_tzjlc = Xjlbsj::selectRaw("SUM(fee_kg)+SUM(fee_ct)as fee")->where('name', '投资活动产生的现金流量净额')->first()->fee;
        //货币资金余额45
        $fee_xjlqk_hbzjye = Xjlbsj::selectRaw("SUM(fee_kg)+SUM(fee_ct)as fee")->where('name', '货币资金余额')->first()->fee;


        //期出现金余额全年合计
        $qcxjye_hj = Xjlbyg::where('project', '期初现金余额')->selectRaw("fee_1+fee_2+fee_3+fee_4+fee_5+fee_6+fee_7+fee_8+fee_9+fee_10+fee_11+fee_12 as fee")->first()->fee;
        //期末现金余额
        $qmxjye_hj = Xjlbyg::where('project', '期末现金余额(资金缺口）')->selectRaw("fee_1+fee_2+fee_3+fee_4+fee_5+fee_6+fee_7+fee_8+fee_9+fee_10+fee_11+fee_12 as fee")->first()->fee;
        //控股预计现金流流出46
        $fee_xjlqkyj_kgxjjlc = ($qmxjye_hj-$qcxjye_hj)/10000;

        //预计经营现金净流出47
        $jyxjjlc = Xjlbyg::where('project', '一、经营现金净流入')->selectRaw("fee_1+fee_2+fee_3+fee_4+fee_5+fee_6+fee_7+fee_8+fee_9+fee_10+fee_11+fee_12 as fee")->first()->fee;
        $fee_xjlqkyj_xjjlc = $jyxjjlc/10000;

        //预计融资净流出48
        $rzjlc = Xjlbyg::where('project', '二、融资现金净流出')->selectRaw("fee_1+fee_2+fee_3+fee_4+fee_5+fee_6+fee_7+fee_8+fee_9+fee_10+fee_11+fee_12 as fee")->first()->fee;
        $fee_xjlqkyj_rzjlc = $rzjlc/10000;

        //投资净流出49
        $tzjlc = Xjlbyg::where('project', '三、投资现金净流出(新兴产业)')->selectRaw("fee_1+fee_2+fee_3+fee_4+fee_5+fee_6+fee_7+fee_8+fee_9+fee_10+fee_11+fee_12 as fee")->first()->fee;
        $fee_xjlqkyj_tzjlc = $tzjlc/10000;

        //资金缺口50
        $fee_xjlqkyj_zjqk = $qmxjye_hj/10000;

        $this->saveDocx([
            'fee_xjlqk_mgsxjljc' => $fee_xjlqk_mgsxjljc/10000,
            'fee_xjlqk_jyxjljc' => $fee_xjlqk_jyxjljc/10000,
            'fee_xjlqk_rzjlc' => $fee_xjlqk_rzjlc/10000,
            'fee_xjlqk_tzjlc' => $fee_xjlqk_tzjlc/10000,
            'fee_xjlqk_hbzjye' => $fee_xjlqk_hbzjye/10000,

            'fee_xjlqkyj_kgxjjlc' => $fee_xjlqkyj_kgxjjlc,
            'fee_xjlqkyj_xjjlc' => $fee_xjlqkyj_xjjlc,
            'fee_xjlqkyj_rzjlc' => $fee_xjlqkyj_rzjlc,
            'fee_xjlqkyj_tzjlc' => $fee_xjlqkyj_tzjlc,
            'fee_xjlqkyj_zjqk' => $fee_xjlqkyj_zjqk,
        ]);
    }

    public function updateText($data){
        $cace_data = Redis::get($this->key);
        $cace_data = empty($cace_data) ? [] : json_decode($cace_data, true);
        foreach ($data as $k=>$v){
            $cace_data[$k] = $v;
        }
        $this->saveDocx($cace_data);
    }

}
