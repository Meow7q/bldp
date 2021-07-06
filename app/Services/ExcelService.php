<?php


namespace App\Services;


use App\Enum\DkzlFxType2;
use App\Enum\Area;
use App\Enum\ImportStatus;
use App\Models\ImportData\Dkzlfx;
use App\Models\ImportData\Dqtx;
use App\Models\ImportData\FileBldp;
use App\Models\ImportData\Fyztqk;
use App\Models\ImportData\Jyzl;
use App\Models\ImportData\Zjbl;
use App\Models\ImportData\Zqzch;
use Illuminate\Support\Facades\DB;
use Rap2hpoutre\FastExcel\FastExcel;

class ExcelService
{
    //
    protected $cell_len = 12;

    //经营质量数据
    protected $jyzl_data = [];

    //房押总体情况介绍（南京）
    protected $fyztqk_data_nj = [];

    //房押总体情况介绍（苏州）
    protected $fyztqk_data_sz = [];

    //贷款质量分析(南京)
    protected $dkzlfx_nj = [];

    //贷款质量分析(杭州)
    protected $dkzlfx_sz = [];

    //资金保理
    protected $zjbl_data = [];

    //证券资产化
    protected $zqzch_data = [];

    //到期提醒
    protected $dqtx_data = [];

    protected $year = null;

    protected $id = null;

    public function import($id, $year, $path)
    {
        $this->year = $year;

        $this->id = $id;

        $collection = (new FastExcel())->import(public_path($path));
        foreach ($collection as $k => $line) {
            //0到5列是"经营质量数据"
            if ($k <= 5) {
               array_push($this->jyzl_data, $line);
                continue;
            }
            //6到18列是"房押总体情况介绍（南京）"
            if ($k <= 18) {
                array_push($this->fyztqk_data_nj, $line);
                continue;
            }
            //19到88是"贷款质量分析(南京)"
            if($k <= 88){
                array_push($this->dkzlfx_nj, $line);
                continue;
            }
            //89到101是"房押总体情况介绍（苏州"
            if($k <= 101){
                array_push($this->fyztqk_data_sz, $line);
                continue;
            }
            //102到171是"贷款质量分析（苏州"
            if($k <= 171){
                array_push($this->dkzlfx_sz, $line);
                continue;
            }

            //172到176是"资金保理"
            if($k <= 176){
                array_push($this->zjbl_data, $line);
                continue;
            }

            //177到184是"资产证券化"
            if($k <= 184){
                array_push($this->zqzch_data, $line);
                continue;
            }

            //185到186是"到期提醒"
            if($k <= 186){
                array_push($this->dqtx_data, $line);
                continue;
            }

        }
        try {
            DB::beginTransaction();
            $this->getJyzlData();
            $this->getFyztqkData($this->fyztqk_data_nj, Area::AREA_NANJING);
            $this->getDkzlfxData($this->dkzlfx_nj, Area::AREA_NANJING);
            $this->getFyztqkData($this->fyztqk_data_sz, Area::AREA_SUZHOU);
            $this->getDkzlfxData($this->dkzlfx_sz, Area::AREA_SUZHOU);
            $this->getZjblData();
            $this->getZqzchData();
            $this->getDqtx();
            FileBldp::where('id',$this->id)
                ->update([
                    'import_status' => ImportStatus::STATUS_SUCCESS
                ]);
            DB::commit();
        }catch (\Exception $e){
            DB::rollBack();
            FileBldp::where('id', $this->import())
                ->update([
                    'import_status' => ImportStatus::STATUS_FAIL
                ]);
        }

    }

    /**
     * 保存经营质量数据
     */
    public function getJyzlData()
    {
        //经营质量数据所包含的字段
        $field = ['ljtf', 'yue', 'zyzjzb', 'sxsr', 'sxlr', 'tfl'];
        //每个月的上方字段的数据
        $jyzl_per_month = [];
        for ($i = 1; $i <= $this->cell_len; $i++) {
            $month_data = array_column($this->jyzl_data, $i, null);
            if (!$month_data) {
                continue;
            }
            foreach ($field as $k1 => $v1) {
                $jyzl_per_month[$i][$v1] = $month_data[$k1] ?: 0;
            }
        }
        foreach ($jyzl_per_month as $k => $per_month) {
            Jyzl::firstOrCreate(['year' => $this->year, 'month' => $k], $per_month);
            //Jyzl::updateOrCreate(['year' => $this->year, 'month' => $k], $per_month);
        }
    }

    /**
     * 房押总体情况
     * @param $data 数据
     * @param $area_id 区域id
     */
    public function getFyztqkData($data, $area_id){
        //房押总体情况所包含的字段
        $field = ['lx75_1', 'lx75_2', 'lx7_6', 'lx7_7', 'pjpgj', 'hjs', 'hkbj', 'tfl', 'tfbs', 'bsny', 'zhdyl', 'yybl', 'eybl'];
        //每个月的上方字段的数据
        $fyztqk_per_month = [];
        for ($i = 1; $i <= $this->cell_len; $i++) {
            $month_data = array_column($data, $i, null);
            if (!$month_data) {
                continue;
            }
            foreach ($field as $k1 => $v1) {
                $fyztqk_per_month[$i][$v1] = $month_data[$k1] ?: 0;
            }
        }
        foreach ($fyztqk_per_month as $k => $per_month) {
            Fyztqk::firstOrCreate(['area_id' => $area_id,'year' => $this->year, 'month' => $k], $per_month);
        }
    }

    /**
     * 贷款质量分析
     * @param $data 数据
     */
    public function getDkzlfxData($data, $area_id){
        //贷款质量分析字段
        $field = ['tfbs', 'bszb', 'fkje', 'jezb', 'eybl'];
        foreach ($this->dkzlfx_nj as $k => $line){
            for ($i = 1; $i <= $this->cell_len; $i++) {
                $month_data = array_column($data, $i, null);
                if (!$month_data) {
                    continue;
                }
                //每个月的二级分类的数据
                $dkzlfx_per_month = [];
                foreach ($month_data as $k1 => $v1){
                    $type = DkzlFxType2::TYPE2_ARR[intval($k1/5)];
                    $c_field = $field[$k1%5];
                    $dkzlfx_per_month[$type['id']][$c_field] = $v1?:0;
                    $dkzlfx_per_month[$type['id']]['area_id'] = $area_id;
                    $dkzlfx_per_month[$type['id']]['year'] = $this->year;
                    $dkzlfx_per_month[$type['id']]['month'] = $i;
                    $dkzlfx_per_month[$type['id']]['type1'] = $type['pid'];
                    $dkzlfx_per_month[$type['id']]['type1_name'] = $type['p_name'];
                    $dkzlfx_per_month[$type['id']]['type2'] = $type['id'];
                    $dkzlfx_per_month[$type['id']]['type2_name'] = $type['name'];
                }
                foreach ($dkzlfx_per_month as $v2){
                    Dkzlfx::firstOrCreate(
                        [
                            'year' => $v2['year'],
                            'month' => $v2['month'],
                            'type1' => $v2['type1'],
                            'type2' => $v2['type2'],
                            'area_id' => $v2['area_id'],
                        ],
                        $v2
                    );
                }
            }
        }
    }

    /**
     *资金保理
     */
    public function getZjblData(){
        //资金保理数据所包含的字段
        $field = ['ypjlr', 'zpjlr', 'dysr', 'zsr', 'tfl'];
        //每个月的上方字段的数据
        $zjbl_per_month = [];
        for ($i = 1; $i <= $this->cell_len; $i++) {
            $month_data = array_column($this->zjbl_data, $i, null);
            if (!$month_data) {
                continue;
            }
            foreach ($field as $k1 => $v1) {
                $zjbl_per_month[$i][$v1] = $month_data[$k1] ?: 0;
            }
        }
        foreach ($zjbl_per_month as $k => $per_month) {
            Zjbl::firstOrCreate(['year' => $this->year, 'month' => $k], $per_month);
        }
    }

    /**
     * 证券资产化
     */
    public function getZqzchData(){
        //证券资产化数据所包含的字段
        $field = ['zgm', 'xmmc', 'hxqy', 'glgm', 'll', 'fxbs', 'jhglr', 'jycs'];
        $zqzch_per_project = [];
        for ($i = 1; $i <= $this->cell_len; $i++) {
            $per_project = array_column($this->zqzch_data, $i, null);
            if (!$per_project) {
                continue;
            }
            foreach ($field as $k1 => $v1) {
                $zqzch_per_project[$i][$v1] = $per_project[$k1] ?: 0;
            }
        }
        foreach ($zqzch_per_project as $k => $per_project) {
            if(!$per_project['zgm'] || !$per_project['xmmc'] || !$per_project['hxqy']){
                continue;
            }
            Zqzch::firstOrCreate($per_project, $per_project);
        }
    }

    /**
     * 到期提醒
     */
    public function getDqtx(){
        //资金保理数据所包含的字段
        $field = ['wdqbs', 'wdqje'];
        //每个月的上方字段的数据
        $dqtx_per_month = [];
        for ($i = 1; $i <= $this->cell_len; $i++) {
            $month_data = array_column($this->dqtx_data, $i, null);
            if (!$month_data) {
                continue;
            }
            foreach ($field as $k1 => $v1) {
                $dqtx_per_month[$i][$v1] = $month_data[$k1] ?: 0;
            }
        }
        foreach ($dqtx_per_month as $k => $per_month) {
            Dqtx::firstOrCreate(['year' => $this->year, 'month' => $k], $per_month);
        }
    }
}
