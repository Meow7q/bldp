<?php


namespace App\Services\Admin;


use App\Enum\FinalizeStatus;
use App\Models\PCompanyCheck\Dwtzqk;
use App\Models\PCompanyCheck\Fhmx;
use App\Models\PCompanyCheck\FhmxNew;
use App\Models\PCompanyCheck\Kjbb;
use App\Models\PCompanyCheck\KjbbNew;
use App\Models\PCompanyCheck\Lnzc;
use App\Models\PCompanyCheck\LnzcNew;
use App\Models\PCompanyCheck\Srhz;
use App\Models\PCompanyCheck\SrhzNew;
use App\Models\PCompanyCheck\TableList;
use App\Models\PCompanyCheck\Xjlbsj;
use App\Models\PCompanyCheck\Xjlbyg;
use App\Models\PCompanyCheck\Ysbmb;
use App\Models\PCompanyCheck\Ysjlcb;
use App\Models\PCompanyCheck\Zbqk;
use App\Models\PCompanyCheck\ZbqkNew;
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


    /**
     * @param $data
     */
    public function exportDocx()
    {
        $cache_data = Redis::get($this->key);
        $cache_data = empty($cache_data) ? [] : json_decode($cache_data, true);
        $this->docx->setValues($cache_data);
        $file_name = '母公司经营管理指标成果点检表'.uniqid();
        $path = '/static/docx/'.$file_name.'.docx';
        $cache_data['current_month'] = $cache_data['data_source_month']??Carbon::now()->month;
        $cache_data['docx_path'] = $path;
        Redis::set($this->key, json_encode($cache_data, JSON_UNESCAPED_UNICODE));
        $this->docx->saveAs(public_path($path));
    }

    /**
     * @param $data
     */
    public function saveDocxData($data){
        $cache_data = Redis::get($this->key);
        $cache_data = empty($cache_data) ? [] : json_decode($cache_data, true);
        foreach ($data as $k => $v) {
            $cache_data[$k] = $v;
        }
        $cache_data['current_month'] = Carbon::now()->month;
        Redis::set($this->key, json_encode($cache_data, JSON_UNESCAPED_UNICODE));
    }


    /**
     * 历年资产详情
     */
    public function statisticsLnzcxq()
    {
        $fee_kg = LnzcNew::select(DB::raw("hj as fee"))
            ->where('project', '小计')
            ->where('unit', '控股')
            ->first();
        $fee_ct = LnzcNew::select(DB::raw("hj as fee"))
            ->where('unit', '合计')
            ->first();
        $fee_total = LnzcNew::select(DB::raw("hj as fee"))
            ->orderBy('id', 'desc')
            ->first();
        $data = [
            'fee_kg' => bcdiv($fee_kg->fee, 10000, 8),
            'fee_ct' => bcdiv($fee_ct->fee, 10000, 8),
            'fee_total' => bcdiv($fee_total->fee, 10000, 8),
        ];
        $this->saveDocxData($data);
    }

    /**
     * 资本情况
     */
    public function statisticsZbqk()
    {
        //自有资金
        $fee_kg_zyzj = ZbqkNew::query()->where('type', '控股')->sum('hbzj');
        $fee_ct_zyzj = ZbqkNew::query()->where('type', '城投')->sum('hbzj');

        //外部融资
        $fee_kg_wbrz = ZbqkNew::query()->where('type', '控股')->sum('rzbj');
        $fee_ct_wbrz = ZbqkNew::query()->where('type', '城投')->sum('rzbj');

        //占用产业资金
        $fee_kg_zycyzj = ZbqkNew::query()->where('type', '控股')->sum('zycyzj');
        $fee_ct_zycyzj = ZbqkNew::query()->where('type', '城投')->sum('zycyzj');

        //合计投入
        $fee_kg_hjtr = ZbqkNew::query()->where('type', '控股')->sum('hjtr');
        $fee_ct_hjtr = ZbqkNew::query()->where('type', '城投')->sum('hjtr');

        //历年费用支出
        $fee_kg_lnfyzc = ZbqkNew::query()->where('type', '控股')->sum('lnfyzc');
        $fee_ct_lnfyzc = ZbqkNew::query()->where('type', '城投')->sum('lnfyzc');

        //形成资产
        $fee_kg_xczc = ZbqkNew::query()->where('type', '控股')->sum('zc');
        $fee_ct_xczc = ZbqkNew::query()->where('type', '城投')->sum('zc');

        $data = [
            'fee_kg_zyzj' => bcdiv($fee_kg_zyzj, 10000, 8),
            'fee_kg_wbrz' => bcdiv($fee_kg_wbrz, 10000, 8),
            'fee_kg_zycyzj' => bcdiv($fee_kg_zycyzj, 10000, 8),
            'fee_kg_hjtr' => bcdiv($fee_kg_hjtr, 10000, 8),
            'fee_kg_lnfyzc' => bcdiv($fee_kg_lnfyzc, 10000, 8),
            'fee_kg_xczc' => bcdiv($fee_kg_xczc, 10000, 8),

            'fee_ct_zyzj' => bcdiv($fee_ct_zyzj,10000, 8),
            'fee_ct_wbrz' => bcdiv($fee_ct_wbrz, 10000, 8),
            'fee_ct_zycyzj' => bcdiv($fee_ct_zycyzj, 10000, 8),
            'fee_ct_hjtr' => bcdiv($fee_ct_hjtr, 10000, 8),
            'fee_ct_lnfyzc' => bcdiv($fee_ct_lnfyzc, 10000, 8),
            'fee_ct_xczc' => bcdiv($fee_ct_xczc, 10000, 8),
        ];
        $this->saveDocxData($data);
    }

    /**
     * 会计报表情况
     */
    public function statisticsKjbbqk(){
        //各项收入17
        $fee_kg_gxsr = KjbbNew::selectRaw("SUM(kg_2022) as fee")
                ->whereIn('project', ['营业收入', '投资收益', '其他收益', '营业外收入'])
                ->first()->fee;
        //各项支出18
        $fee_kg_gxzc = KjbbNew::selectRaw("SUM(kg_2022) as fee")
            ->whereIn('project', ['营业支出', '管理费用', '财务费用', '税金及附加', '营业外及其他支出', '所得税'])
            ->first()->fee;

        //会计盈利16
        $fee_kg_kjyl = KjbbNew::selectRaw("kg_2022 as fee")
            ->where('project', '当年净利润')
            ->first()->fee;
        $fee_ct_kjyl = KjbbNew::selectRaw("ct_2022 as fee")
            ->where('project', '当年净利润')
            ->first()->fee;
        //管理费用(控股)19
        $fee_kg_glfy = KjbbNew::selectRaw("kg_2022 as fee")
            ->where('project', '管理费用')
            ->first()->fee;

        //城投
        //投资收益21
        $fee_ct_tzss = KjbbNew::selectRaw("ct_2022 as fee")
            ->where('project', '投资收益')
            ->first()->fee;
        //财务费用22
        $fee_ct_cwfy = KjbbNew::selectRaw("ct_2022 as fee")
            ->where('project', '财务费用')
            ->first()->fee;
        //会计亏损23
        $fee_kjks = KjbbNew::selectRaw("kg_2022+ct_2022 as fee")
            ->where('project', '当年净利润')
            ->first()->fee;

        $this->saveDocxData([
            'fee_kg_kjyl' => bcdiv($fee_kg_kjyl, 10000, 8),
            'fee_kg_gxsr' => bcdiv($fee_kg_gxsr, 10000, 8),
            'fee_kg_gxzc' => bcdiv($fee_kg_gxzc, 10000, 8),
            'fee_kg_glfy' => bcdiv($fee_kg_glfy, 10000, 8),

            'fee_ct_kjyl' => bcdiv($fee_ct_kjyl, 10000, 8),
            'fee_ct_tzss' => bcdiv($fee_ct_tzss, 10000, 8),
            'fee_ct_cwfy' => bcdiv($fee_ct_cwfy, 10000, 8),
            'fee_kjks' => bcdiv($fee_kjks, 10000, 8),
        ]);
    }

    /**
     * 收入情况
     */
    public function statisticsSrqk(){
        //控股及城投收入合计24
        $fee_srqk_srhj = SrhzNew::selectRaw('fee_2022 as fee')
            ->where('type', '累计')
            ->where('unit', '合计')
            ->first()->fee;

        //投资收益25
        $fee_srqk_tzss = SrhzNew::selectRaw('fee_2022 as fee')
            ->where('type', '投资收益')
            ->where('unit', '合计')
            ->first()->fee;

        //借款利息26
        $fee_srqk_jklx =  SrhzNew::selectRaw('fee_2022 as fee')
            ->where('type', '利息')
            ->where('unit', '合计')
            ->first()->fee;

        //担保费27
        $fee_srqk_dbf = SrhzNew::selectRaw('fee_2022 as fee')
            ->where('type', '担保费对外')
            ->where('unit', '合计')
            ->first()->fee;

        //分红28
        $fee_srqk_fh = SrhzNew::selectRaw('fee_2022 as fee')
            ->where('type', '分红')
            ->where('unit', '合计')
            ->first()->fee;

        $this->saveDocxData([
            'fee_srqk_srhj' => bcdiv($fee_srqk_srhj, 10000, 8),
            'fee_srqk_tzss' => bcdiv($fee_srqk_tzss, 10000, 8),
            'fee_srqk_jklx' => bcdiv($fee_srqk_jklx, 10000, 8),
            'fee_srqk_dbf' => bcdiv($fee_srqk_dbf, 10000, 8),
            'fee_srqk_fh' => bcdiv($fee_srqk_fh, 10000, 8),
        ]);
    }

    /**
     * 分红情况
     */
    public function statisticsFhqk(){
        $fee_fhqk_kgfh = FhmxNew::selectRaw("SUM(fee_2022) as fee")->whereIn('name', ['小计','中南装饰'])->first()->fee;
        $fee_fhqk_ctfh = FhmxNew::selectRaw('fee_2022 as fee')->where('name', '江苏中南建设集团有限公司')->first()->fee;
        $this->saveDocxData([
            'fee_fhqk_kgfh' => $fee_fhqk_kgfh,
            'fee_fhqk_ctfh' => $fee_fhqk_ctfh,
        ]);
    }

    /**
     * 部门预算情况
     */
    public function statisticsBmysqk(){
        //各部门预算数31
        $fee_ysqk_gbmyss = Ysbmb::selectRaw("fee_e as fee")->where('type', '费用合计')->first()->fee;
        //实际发生数32
        $fee_ysqk_sjfss = Ysbmb::selectRaw("fee_d as fee")->where('type', '费用合计')->first()->fee;
        //执行率
        $fee_ysqk_zxl = Ysbmb::selectRaw("de as zxl")->where('type', '费用合计')->first()->zxl;

        $this->saveDocxData([
            'fee_ysqk_gbmyss' => bcdiv($fee_ysqk_gbmyss, 100000000, 8),
            'fee_ysqk_sjfss' => bcdiv($fee_ysqk_sjfss, 100000000, 8),
            'fee_ysqk_zxl' => round($fee_ysqk_zxl*100, 2).'%',
        ]);
    }

    /**
     *
     * 经理层预算情况
     */
    public function statisticsJlcysqk(){
        //控股经理层预算数34
        $fee_ysqk_kgjlcyss = Ysjlcb::selectRaw("fee_e as fee")->where('name', '经理层费用小计')->first()->fee;
        //经理层实际发生数35
        $fee_ysqk_ylcsjfss = Ysjlcb::selectRaw("fee_d as fee")->where('name', '经理层费用小计')->first()->fee;
        //经理层执行率36
        $fee_ysqk_jlczxl = Ysjlcb::selectRaw("de as zxl")->where('name', '经理层费用小计')->first()->zxl;

        $this->saveDocxData([
            'fee_ysqk_kgjlcyss' => bcdiv($fee_ysqk_kgjlcyss, 10000, 8),
            'fee_ysqk_ylcsjfss' => bcdiv($fee_ysqk_ylcsjfss, 10000, 8),
            'fee_ysqk_jlczxl' => round($fee_ysqk_jlczxl*100, 2).'%',
        ]);
    }

    /**
     * 对外投资情况
     */
    public function statisticsDwtzqk(){
        //股权投资37
        $fee_dwtzqk_hjgqtz = Dwtzqk::selectRaw("gqbj+ysgx as fee")
            ->where('unit', '合计')
            ->first()->fee;
        //债权投资38
        $fee_dwtzqk_zqtz = Dwtzqk::selectRaw("zq+yszx as fee")
            ->where('unit', '合计')
            ->first()->fee;
        //投资的留存收益39
        $fee_dwtzqk_lcsy = Dwtzqk::selectRaw("lcsy as fee")
            ->where('unit', '合计')
            ->first()->fee;
        //合计投入40
        $fee_dwtzqk_hjtr = Dwtzqk::selectRaw("hj as fee")
            ->where('unit', '合计')
            ->first()->fee;

        $this->saveDocxData([
            'fee_dwtzqk_hjgqtz' => bcdiv($fee_dwtzqk_hjgqtz, 10000, 8),
            'fee_dwtzqk_zqtz' => bcdiv($fee_dwtzqk_zqtz, 10000, 8),
            'fee_dwtzqk_lcsy' => bcdiv($fee_dwtzqk_lcsy, 10000, 8),
            'fee_dwtzqk_hjtr' => bcdiv($fee_dwtzqk_hjtr, 10000, 8),
        ]);
    }

    /**
     * 现金流去情况
     */
    public function statisticsXjlqk(){
        //母公司现金流净出41
        $fee_xjlqk_mgsxjljc = Xjlbsj::selectRaw("hj as fee")->where('name', '合计')->first()->fee;
        //经营现金流净出42
        $fee_xjlqk_jyxjljc = Xjlbsj::selectRaw("hj as fee")->where('name', '经营活动产生的现金流量净额')->first()->fee;
        //融资净流出43
        $fee_xjlqk_rzjlc = Xjlbsj::selectRaw("hj as fee")->where('name', '融资活动产生的现金流量净额')->first()->fee;
        //投资净流出44
        $fee_xjlqk_tzjlc = Xjlbsj::selectRaw("hj as fee")->where('name', '投资活动产生的现金流量净额')->first()->fee;
        //货币资金余额45
        $fee_xjlqk_hbzjye = Xjlbsj::selectRaw("hj as fee")->where('name', '货币资金余额')->first()->fee;

        $data =[
            'fee_xjlqk_mgsxjljc' => bcdiv($fee_xjlqk_mgsxjljc, 10000, 8),
            'fee_xjlqk_jyxjljc' => bcdiv($fee_xjlqk_jyxjljc, 10000, 8),
            'fee_xjlqk_rzjlc' => bcdiv($fee_xjlqk_rzjlc,10000,8),
            'fee_xjlqk_tzjlc' => bcdiv($fee_xjlqk_tzjlc,10000,8),
            'fee_xjlqk_hbzjye' => bcdiv($fee_xjlqk_hbzjye, 10000,8),
        ];
        $this->saveDocxData($data);
    }

    /**
     *
     */
    public function statisticsXjlqkYg(){

        //控股预计现金流流出46
        $fee_xjlqkyj_kgxjjlc = Xjlbyg::where('project', '现金净流出')->selectRaw("hj as fee")->first()->fee;
        $fee_xjlqkyj_kgxjjlc = bcdiv($fee_xjlqkyj_kgxjjlc, 10000, 8);

        //预计经营现金净流出47
        $jyxjjlc = Xjlbyg::where('project', '一、经营现金净流入')->selectRaw("hj as fee")->first()->fee;
        $fee_xjlqkyj_xjjlc = bcdiv($jyxjjlc, 10000, 8);

        //预计融资净流出48
        $rzjlc = Xjlbyg::where('project', '二、融资现金净流出')->selectRaw("hj as fee")->first()->fee;
        $fee_xjlqkyj_rzjlc = bcdiv($rzjlc, 10000, 8);

        //投资净流出49
        $tzjlc = Xjlbyg::where('project', '三、投资现金净流出(新兴产业)')->selectRaw("hj as fee")->first()->fee;
        $fee_xjlqkyj_tzjlc = bcdiv($tzjlc, 10000, 8);

        //资金缺口50
        $zjqk = Xjlbyg::where('project', '期末现金余额(资金缺口）')->selectRaw("hj as fee")->first()->fee;
        $fee_xjlqkyj_zjqk = bcdiv($zjqk, 10000, 8);

        $data =[
            'fee_xjlqkyj_kgxjjlc' => $fee_xjlqkyj_kgxjjlc,
            'fee_xjlqkyj_xjjlc' => $fee_xjlqkyj_xjjlc,
            'fee_xjlqkyj_rzjlc' => $fee_xjlqkyj_rzjlc,
            'fee_xjlqkyj_tzjlc' => $fee_xjlqkyj_tzjlc,
            'fee_xjlqkyj_zjqk' => $fee_xjlqkyj_zjqk,
        ];
        $this->saveDocxData($data);
    }

    /**
     * @param $data
     */
    public function updateText($data){
        $cache_data = Redis::get($this->key);
        $cache_data = empty($cace_data) ? [] : json_decode($cache_data, true);
        foreach ($data as $k=>$v){
            $cache_data[$k] = $v;
        }
        $this->saveDocxData($cache_data);
        $this->exportDocx();
    }


    /**
     * 重置统计数据
     */
    public function resetStatisticsData($month){
        $data = [
            'data_source_month' => $month,
            'docx_path' => '',
            'fee_xjlqk_mgsxjljc' => '',
            'fee_xjlqk_jyxjljc' => '',
            'fee_xjlqk_rzjlc' => '',
            'fee_xjlqk_tzjlc' => '',
            'fee_xjlqk_hbzjye' => '',
            'fee_xjlqkyj_kgxjjlc' => '',
            'fee_xjlqkyj_xjjlc' => '',
            'fee_xjlqkyj_rzjlc' => '',
            'fee_xjlqkyj_tzjlc' => '',
            'fee_xjlqkyj_zjqk' => '',
            'current_month' => '',
            'fee_dwtzqk_hjgqtz' => '',
            'fee_dwtzqk_zqtz' => '',
            'fee_dwtzqk_lcsy' => '',
            'fee_dwtzqk_hjtr' => '',
            'fee_kg' => '',
            'fee_ct' => '',
            'fee_total' => '',
            'fee_kg_kjyl' => '',
            'fee_kg_gxsr' => '',
            'fee_kg_gxzc' => '',
            'fee_kg_glfy' => '',
            'fee_ct_kjyl' => '',
            'fee_ct_tzss' => '',
            'fee_ct_cwfy' => '',
            'fee_kjks' => '',
            'fee_ysqk_kgjlcyss' => '',
            'fee_ysqk_ylcsjfss' => '',
            'fee_ysqk_jlczxl' => '',
            'fee_ysqk_gbmyss' => '',
            'fee_ysqk_sjfss' => '',
            'fee_ysqk_zxl' => '',
            'fee_fhqk_kgfh' => '',
            'fee_fhqk_ctfh' => '',
            'fee_srqk_srhj' => '',
            'fee_srqk_tzss' => '',
            'fee_srqk_jklx' => '',
            'fee_srqk_dbf' => '',
            'fee_srqk_fh' => '',
            'fee_kg_zyzj' => '',
            'fee_kg_wbrz' => '',
            'fee_kg_zycyzj' => '',
            'fee_kg_hjtr' => '',
            'fee_kg_lnfyzc' => '',
            'fee_kg_xczc' => '',
            'fee_ct_zyzj' => '',
            'fee_ct_wbrz' => '',
            'fee_ct_zycyzj' =>'',
            'fee_ct_hjtr' => '',
            'fee_ct_lnfyzc' => '',
            'fee_ct_xczc' => '',
            'text1' => '',
            'text2' => '',
            'text3' => '',
            'text4' => '',
            'text5' => '',
            'text6' => '',
            'text7' => '',
            'text8' => '',
            'text9' => '',
            'text10' => ''
        ];
        //$this->saveDocx($data);
        Redis::set($this->key, json_encode($data, JSON_UNESCAPED_UNICODE));
    }

    /**
     * 下载列表
     * @return array
     */
    public function downlist(){
        $data = Redis::get($this->key);
        $data = json_decode($data, true);
        $month = $data['data_source_month']??null;
        $file_list = [];
        if(!empty($data['docx_path'])){
            //array_push($file_list, $data['docx_path']);
        }
        $table_list = ['lnzc', 'zbqk', 'kjbb', 'srhz', 'fhmx', 'ysbmb', 'ysjlcb', 'dwtzqk', 'xjlbsj', 'xjlbyg'];
        foreach ($table_list as $table_name){
            $info = TableList::where('table_name', $table_name)
                ->when(!empty($month), function ($query) use ($month){
                    $query->where('month', $month)
                        ->where('status', FinalizeStatus::YES);
                })
                ->orderBy('created_at', 'desc')
                ->first();
            if($info){
                array_push($file_list, $info->file_path);
            }
        }
        return $file_list;
    }
}
