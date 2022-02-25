<?php


namespace App\Services\Admin;

use App\Models\PCompanyCheck\Dwtzqk;
use App\Models\PCompanyCheck\Fhmx;
use App\Models\PCompanyCheck\Kjbb;
use App\Models\PCompanyCheck\Lnzc;
use App\Models\PCompanyCheck\Srhz;
use App\Models\PCompanyCheck\TableList;
use App\Models\PCompanyCheck\Xjlbsj;
use App\Models\PCompanyCheck\Xjlbyg;
use App\Models\PCompanyCheck\Ysbmb;
use App\Models\PCompanyCheck\Ysjlcb;
use App\Models\PCompanyCheck\Zbqk;
use Illuminate\Support\Facades\DB;
use Rap2hpoutre\FastExcel\FastExcel;

class CheckService
{
    protected $data_service;

    public function __construct(PCompanyDatastaticsService $data_service)
    {
        $this->data_service = $data_service;
    }

    /**
     * @param $path
     * @param $table_name
     * @throws \Box\Spout\Common\Exception\IOException
     * @throws \Box\Spout\Common\Exception\UnsupportedTypeException
     * @throws \Box\Spout\Reader\Exception\ReaderNotOpenedException
     */
    public function importExcel($path, $table_name)
    {
        $collection = (new FastExcel())->import(public_path('upload/' . $path));
        $line_count = count($collection);
        switch ($table_name) {
            case 'lnzc':
                if($line_count != 13){
                    throw new \Exception('模版错误');
                }
                $this->lnzc($collection);
                $this->data_service->statisticsLnzcxq();
                break;
            case 'zbqk':
                if($line_count != 2){
                    throw new \Exception('模版错误');
                }
                $this->zbqk($collection);
                $this->data_service->statisticsZbqk();
                break;
            case 'kjbb':
                if($line_count != 12){
                    throw new \Exception('模版错误');
                }
                $this->kjbb($collection);
                $this->data_service->statisticsKjbbqk();
                break;
            case 'srhz':
                if($line_count != 16){
                    throw new \Exception('模版错误');
                }
                $this->srhz($collection);
                $this->data_service->statisticsSrqk();
                break;
            case 'fhmx':
                if($line_count != 8){
                    throw new \Exception('模版错误');
                }
                $this->fhmx($collection);
                $this->data_service->statisticsFhqk();
                break;
            case 'ysbmb':
                if($line_count != 19){
                    throw new \Exception('模版错误');
                }
                $this->ysbmb($collection);
                $this->data_service->statisticsBmysqk();
                break;
            case 'ysjlcb':
                if($line_count != 15){
                    throw new \Exception('模版错误');
                }
                $this->ysjlcb($collection);
                $this->data_service->statisticsJlcysqk();
                break;
            case 'dwtzqk':
                if($line_count != 10){
                    throw new \Exception('模版错误');
                }
                $this->dwtzqk($collection);
                $this->data_service->statisticsDwtzqk();
                break;
            case 'xjlbsj':
                if($line_count != 5){
                    throw new \Exception('模版错误');
                }
                $this->xjlbsj($collection);
                $this->data_service->statisticsXjlqk();
                break;
            case 'xjlbyg':
                if($line_count != 20){
                    throw new \Exception('模版错误');
                }
                $this->xjlbyg($collection);
                $this->data_service->statisticsXjlqk();
                break;
        }
        TableList::updateOrCreate(['table_name' => $table_name], [
            'file_path' => $path
        ]);
    }

    /**
     * 历年资产
     * @param $data
     * @throws \Exception
     */
    protected function lnzc($data)
    {
        //yxwzc 营业外支出(捐赠等)
        //cwfy 财务费用
        //gz 工资
        //pgzxf 评估咨询费
        //zj 折旧
        //bgf 办公费
        //ywzdf 业务招待费
        //clf 差旅费
        //qtywcb 其他业务成本
        //kgqt 控股其他

        //ggsjf 广告设计费
        //sds 所得税费用
        //ctqt 城投其他
        $field_arr = ['yxwzc', 'cwfy', 'gz', 'pgzxf', 'zj',
            'bgf', 'ywzdf', 'clf', 'qtywcb', 'kgqt', 'ggsjf',
            'sds', 'ctqt',
        ];
        try {
            DB::beginTransaction();
            Lnzc::truncate();
            foreach ($data as $k => $line) {
                $this->setData($field_arr[$k], $line);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * 历年资产设置数据
     * @param $filed_name
     * @param $line
     */
    protected function setData($filed_name, $line)
    {
        foreach ($line as $k => $v) {
            if (preg_match('/\d{4}年$/', $k)) {
                $year = str_replace('年', '', $k);
                Lnzc::updateOrCreate(['year' => $year], [
                    'year' => $year,
                    $filed_name => is_numeric($v) ? 0 : $v
                ]);
            }
        }
    }

    /**
     * 资本情况
     * @param $data
     * @throws \Exception
     */
    protected function zbqk($data)
    {
        $field_arr = ['type', 'rzbj', 'rzpjcb', 'zycyzj', 'hjtr',
            'lnfyzc', 'zyzj', 'cqgqtz', 'gdzc', 'zmjzc'
        ];
        try {
            DB::beginTransaction();
            Zbqk::truncate();
            foreach ($data as $k => $line) {
                $line = array_values($line);
                Zbqk::updateOrCreate(['type' => $line[0]], [
                    'type' => $line[0],
                    'rzbj' => is_numeric($line[1]) ? $line[1] : 0,
                    'rzpjcb' => is_numeric($line[2]) ? $line[2] : 0,
                    'zycyzj' => is_numeric($line[3]) ? $line[3] : 0,
                    'hjtr' => is_numeric($line[4]) ? $line[4] : 0,
                    'lnfyzc' => is_numeric($line[5]) ? $line[5] : 0,
                    'zyzj' => is_numeric($line[6]) ? $line[6] : 0,
                    'cqgqtz' => is_numeric($line[7]) ? $line[7] : 0,
                    'gdzc' => is_numeric($line[8]) ? $line[8] : 0,
                    'zmjzc' => is_numeric($line[9]) ? $line[9] : 0,
                ]);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * 会计报表
     * @param $data
     * @throws \Exception
     */
    protected function kjbb($data)
    {
        $field_arr = ['yysr', 'tzss', 'qtsy', 'yywsr',
            'yyzc', 'glfy', 'cwfy', 'sjjfj', 'yywjqtzc', 'sds',
            'dnjlr', 'qmwfplr',
        ];
        try {
            DB::beginTransaction();
            Kjbb::truncate();
            foreach ($data as $k1 => $line) {
                foreach ($line as $k2 => $v) {
                    $year = preg_filter('/\D/', '', $k2);
                    if (empty($year)) {
                        continue;
                    }
                    $type = strpos($k2, '控股') !== false ? '控股' : '城投';
                    Kjbb::updateOrCreate(['year' => $year, 'type' => $type], [
                        'year' => $year,
                        'type' => $type,
                        $field_arr[$k1] => is_numeric($v) ? $v : 0
                    ]);
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * @param $data
     * @throws \Exception
     */
    protected function srhz($data)
    {
        $field_arr = [
            'dbfdw', 'lx', 'glf', 'fh', 'tzly', 'ssjl', 'gpdx', 'zj',
            'dbfdw', 'lx', 'glf', 'fh', 'tzly', 'ssjl', 'gpdx', 'zj'
        ];
        try {
            DB::beginTransaction();
            Srhz::truncate();
            foreach ($data as $k1 => $line) {
                foreach ($line as $k2 => $v) {
                    $year = preg_filter('/\D/', '', $k2);
                    if (empty($year)) {
                        continue;
                    }
                    $type = $k1 > 7 ? '城投' : '控股';
                    Srhz::updateOrCreate(['year' => $year, 'type' => $type], [
                        'type' => $type,
                        'year' => $year,
                        $field_arr[$k1] => is_numeric($v) ? $v : 0
                    ]);
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * 分红明细
     * @param $data
     * @throws \Exception
     */
    protected function fhmx($data)
    {
        $field_arr = ['unit', 'company', 'year', 'remark'];
        try {
            DB::beginTransaction();
            Fhmx::truncate();
            foreach ($data as $k1 => $line) {
                $unit = array_shift($line);
                $company = array_shift($line);
                $remark = array_pop($line);
                foreach ($line as $year => $fee) {
                    $year = preg_filter('/\D/', '', $year);
                    Fhmx::updateOrCreate(['unit' => $unit, 'company' => $company, 'year' => $year], [
                        'year' => $year,
                        'fee' => is_numeric($fee) ? $fee : 0,
                        'remark' => $remark
                    ]);
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * 预算部门表
     * @param $data
     * @throws \Exception
     */
    protected function ysbmb($data)
    {
        try {
            DB::beginTransaction();
            Ysbmb::truncate();
            foreach ($data as $k1 => $line) {
                if ($k1 > 0) {
                    $type = array_shift($line);
                    $fee_d = array_shift($line);
                    $fee_e = array_shift($line);
                    $fee_f = array_shift($line);
                    Ysbmb::create([
                        'type' => $type,
                        'fee_d' => is_numeric($fee_d) ? $fee_d : 0,
                        'fee_e' => is_numeric($fee_e) ? $fee_e : 0,
                        'fee_f' => is_numeric($fee_f) ? $fee_f : 0,
                    ]);
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * @param $data
     * @throws \Exception
     */
    protected function ysjlcb($data)
    {
        try {
            DB::beginTransaction();
            Ysjlcb::truncate();
            foreach ($data as $k1 => $line) {
                if ($k1 > 0) {
                    $name = array_shift($line);
                    $fee_d = array_shift($line);
                    $fee_e = array_shift($line);
                    $fee_f = array_shift($line);
                    Ysjlcb::create([
                        'name' => $name,
                        'fee_d' => is_numeric($fee_d) ? $fee_d : 0,
                        'fee_e' => is_numeric($fee_e) ? $fee_e : 0,
                        'fee_f' => is_numeric($fee_f) ? $fee_f : 0,
                    ]);
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * @param $data
     */
    protected function dwtzqk($data)
    {
        try {
            DB::beginTransaction();
            Dwtzqk::truncate();
            foreach ($data as $k1 => $line) {
                $unit = array_shift($line);
                $gqbj = array_shift($line);
                $ysgx = array_shift($line);
                $zq = array_shift($line);
                $yszx = array_shift($line);
                $lcsy = array_shift($line);
                $gxzj = array_shift($line);
                $yfhhysj = array_shift($line);
                $tzhbl = array_shift($line);
                Dwtzqk::create([
                    'unit' => $unit,
                    'gqbj' => is_numeric($gqbj) ? $gqbj : 0,
                    'ysgx' => is_numeric($ysgx) ? $ysgx : 0,
                    'zq' => is_numeric($zq) ? $zq : 0,
                    'yszx' => is_numeric($yszx) ? $yszx : 0,
                    'lcsy' => is_numeric($lcsy) ? $lcsy : 0,
                    'gxzj' => is_numeric($gxzj) ? $gxzj : 0,
                    'yfhhysj' => is_numeric($yfhhysj) ? $yfhhysj : 0,
                    'tzhbl' => is_numeric($tzhbl) ? $tzhbl : 0,
                ]);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * 现金流表-实际
     * @param $data
     * @throws \Exception
     */
    protected function xjlbsj($data)
    {
        try {
            DB::beginTransaction();
            Xjlbsj::truncate();
            foreach ($data as $k1 => $line) {
                $name = array_shift($line);
                $fee_kg = array_shift($line);
                $fee_ct = array_shift($line);
                $hj = array_shift($line);
                Xjlbsj::create([
                    'name' => $name,
                    'fee_kg' => is_numeric($fee_kg) ? $fee_kg : 0,
                    'fee_ct' => is_numeric($fee_ct) ? $fee_ct : 0,
                    'hj' => is_numeric($hj) ? $hj : 0,
                ]);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    protected function xjlbyg($data)
    {
        try {
            DB::beginTransaction();
            Xjlbyg::truncate();
            foreach ($data as $k1 => $line) {
                $project = array_shift($line);
                $hj = array_shift($line);
                $fee_1 = array_shift($line);
                $fee_2 = array_shift($line);
                $fee_3 = array_shift($line);
                $fee_4 = array_shift($line);
                $fee_5 = array_shift($line);
                $fee_6 = array_shift($line);
                $fee_7 = array_shift($line);
                $fee_8 = array_shift($line);
                $fee_9 = array_shift($line);
                $fee_10 = array_shift($line);
                $fee_11 = array_shift($line);
                $fee_12 = array_shift($line);
                Xjlbyg::create([
                    'project' => $project,
                    'hj' => $hj,
                    'fee_1' => is_numeric($fee_1)?$fee_1:0,
                    'fee_2' => is_numeric($fee_2)?$fee_2:0,
                    'fee_3' => is_numeric($fee_3)?$fee_3:0,
                    'fee_4' => is_numeric($fee_4)?$fee_4:0,
                    'fee_5' => is_numeric($fee_5)?$fee_5:0,
                    'fee_6' => is_numeric($fee_6)?$fee_6:0,
                    'fee_7' => is_numeric($fee_7)?$fee_7:0,
                    'fee_8' => is_numeric($fee_8)?$fee_8:0,
                    'fee_9' => is_numeric($fee_9)?$fee_9:0,
                    'fee_10' => is_numeric($fee_10)?$fee_10:0,
                    'fee_11' => is_numeric($fee_11)?$fee_11:0,
                    'fee_12' => is_numeric($fee_12)?$fee_12:0,
                ]);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * @param $table_name
     * @return array
     */
    public function show($table_name)
    {
        if ($table_name == 'fhmx') {
            $fhmx_data = [];
            $company = Fhmx::distinct()->pluck('company')->toArray();
            foreach ($company as $k => $v) {
                $data = Fhmx::where('company', $v)->select(['*'])->orderBy('year', 'desc')->get()->toArray();
                $data_temp = [];
                $data_temp['unit'] = $data[0]['unit'];
                $data_temp['company'] = $data[0]['company'];
                $hz = 0;
                foreach ($data as $v) {
                    $hz += $v['fee'];
                    $data_temp[$v['year']] = $v['fee'];
                }
                $data_temp['hz'] = $hz;
                $data_temp['remark'] = $data[0]['remark'];
                array_push($fhmx_data, $data_temp);
            }
            //小计
            $fhmx_xj = [
                "unit" => "中南控股",
                "company" => "小计",
                "2022" => 0,
                "2021" => 0,
                "2020" => 0,
                "2019" => 0,
                "2018" => 0,
                "2017" => 0,
                "2016" => 0,
                "2015" => 0,
                "2014" => 0,
                "2013" => 0,
                "hz" => 0,
                "remark" => ''
            ];
            $fhmx_hj = [
                "unit" => '',
                "company" => "合计",
                "2022" => 0,
                "2021" => 0,
                "2020" => 0,
                "2019" => 0,
                "2018" => 0,
                "2017" => 0,
                "2016" => 0,
                "2015" => 0,
                "2014" => 0,
                "2013" => 0,
                "hz" => 0,
                "remark" => ''
            ];
            collect($fhmx_data)->map(function ($v) use (&$fhmx_xj, &$fhmx_hj) {
                foreach ($v as $k1 => $v1) {
                    if ($v['unit'] == '中南控股') {
                        if (($k1 != 'remark') && ($k1 != 'company') && ($k1 != 'unit')) {
                            $fhmx_xj[$k1] += $v1;
                        }
                    }
                    if (($k1 != 'remark') && ($k1 != 'company') && ($k1 != 'unit')) {
                        $fhmx_hj[$k1] += $v1;
                    }
                }
            });
            array_push($fhmx_data, $fhmx_xj);
            $fhmx_data = collect($fhmx_data)->sortByDesc('unit')->values()->all();
            array_push($fhmx_data, $fhmx_hj);
            return $fhmx_data;
        }

        if ($table_name == 'lnzc') {
            $field_arr1 = [['yxwzc', '营业外支出(捐赠等)'], ['cwfy', '财务费用'], ['gz', '管理费用-工资'], ['pgzxf', '管理费用-评估咨询费'],
                ['zj', '管理费用-折旧'], ['bgf', '管理费用-办公费'], ['ywzdf', '管理费用-业务招待费'],
                ['clf', '管理费用-差旅费`'], ['qtywcb', '其他业务成本'], ['kgqt', '其他']];
            $field_arr2 = [['ggsjf', '管理费用-广告设计费'], ['sds', '所得税费用'], ['ctqt', '其他']];
            $lnzc_data = [];
            [$hj1, $data1] = $this->lnzcData($field_arr1);
            [$hj2, $data2] = $this->lnzcData($field_arr2);
            array_push($lnzc_data, $data1);
            array_push($lnzc_data, $data2);
            array_push($lnzc_data, ['hj' => $hj1 + $hj2]);
            return $lnzc_data;
        }

        if ($table_name == 'kjbb') {
            $field_arr = [
                ['yysr', '营业收入'],
                ['tzss', '投资收益'],
                ['qtsy', '其他收益'],
                ['yywsr', '营业外收入'],
                ['glfy', '管理费用'],
                ['cwfy', '财务费用'],
                ['sjjfj', '税金及附加'],
                ['yywjqtzc', '营业外及其他支出'],
                ['sds', '所得税'],
                ['dnjlr', '当年净利润'],
                ['qmwfplr', '期末未分配利润'],
            ];
            $kjbb_data = collect($field_arr)->map(function ($v, $k) {
                $data = Kjbb::select("{$v[0]} as fee")->orderBy('type', 'desc')
                    ->orderBy('year', 'desc')->get()->map(function ($v) {
                        return $v->fee;
                    })->values()->all();
                return [
                    'name' => $v[1],
                    '控股2022' => $data[0],
                    '控股2021' => $data[1],
                    '城投同比' => (round(($data[0] - $data[1]) / $data[1], 2) * 100) . '%',
                    '城投2022' => $data[2],
                    '城投2021' => $data[3],
                    '控股同比' => (round(($data[2] - $data[3]) / $data[3], 2) * 100) . '%',
                ];
            })->values()->all();
            return $kjbb_data;
        }

        if ($table_name == 'srhz') {
            $raw1 = "year, SUM(dbfdw) as dbfdw, SUM(lx) as lx, SUM(glf) as glf, SUM(fh) as fh, SUM(tzly) as tzly, SUM(ssjl) as ssjl, SUM(gpdx) as gpdx, SUM(zj) as zj";
            $data1 = Srhz::selectRaw($raw1)->groupBy('year')->orderBy('year', 'desc')->get()->toArray();
            $hj_data = $this->srhzBuild($data1);

            $data2 = Srhz::select(['*'])->where('type', '控股')->orderBy('year', 'desc')->get()->toArray();
            $kg_data = $this->srhzBuild($data2);

            $data3 = Srhz::select(['*'])->where('type', '城投')->orderBy('year', 'desc')->get()->toArray();
            $ct_data = $this->srhzBuild($data3);

            $raw4 = "SUM(dbfdw)+SUM(lx)+SUM(glf)+SUM(fh) +SUM(tzly)+SUM(ssjl)+SUM(gpdx)+SUM(zj) as fee";
            $lj = Srhz::selectRaw($raw4)->first();
            return [
                $hj_data,
                $kg_data,
                $ct_data,
                ['hj' => $lj->fee]
            ];
        }

        if ($table_name == 'ysbmb') {
            $data_ysbmb = Ysbmb::all()->toArray();
            $ysbmb_hj_d = collect($data_ysbmb)->sum('fee_d');
            $ysbmb_hj_e = collect($data_ysbmb)->sum('fee_e');
            $ysbmb_hj_f = collect($data_ysbmb)->sum('fee_f');
            array_push($data_ysbmb, ['type' => '合计', 'fee_d' => $ysbmb_hj_d, 'fee_e' => $ysbmb_hj_e, 'fee_f' => $ysbmb_hj_f]);
            $data_ysbmb = collect($data_ysbmb)->map(function ($v) {
                $v['de'] = (round($v['fee_d'] / $v['fee_e'], 4) * 100) . '%';
                $v['df'] = (round($v['fee_d'] / $v['fee_f'], 4) * 100) . '%';
                return $v;
            })->values()->all();
            return $data_ysbmb;
        }

        if ($table_name == 'ysjlcb') {
            $data_ysjlcb = Ysjlcb::all()->toArray();
            $ysjlcb_hj_d = collect($data_ysjlcb)->sum('fee_d');
            $ysjlcb_hj_e = collect($data_ysjlcb)->sum('fee_e');
            $ysjlcb_hj_f = collect($data_ysjlcb)->sum('fee_f');
            array_push($data_ysjlcb, ['name' => '合计', 'fee_d' => $ysjlcb_hj_d, 'fee_e' => $ysjlcb_hj_e, 'fee_f' => $ysjlcb_hj_f]);
            $data_ysjlcb = collect($data_ysjlcb)->map(function ($v) {
                $v['de'] = (round($v['fee_d'] / $v['fee_e'], 4) * 100) . '%';
                $v['df'] = (round($v['fee_d'] / $v['fee_f'], 4) * 100) . '%';
                return $v;
            })->values()->all();
            return $data_ysjlcb;
        }

        if ($table_name == 'dwtzqk') {
            $data_dwtzqk = Dwtzqk::all()->toArray();
            $dwtzqk_hj = [
                "unit" => "合计",
                "gqbj" => 0,
                "ysgx" => 0,
                "zq" => 0,
                "yszx" => 0,
                "lcsy" => 0,
                "gxzj" => 0,
                "yfhhysj" => 0,
                "tzhbl" => 0,
                'hj' => 0,
            ];
            $data_dwtzqk = collect($data_dwtzqk)->map(function ($v) use (&$dwtzqk_hj) {
                foreach ($v as $k1 => $v1) {
                    if ($k1 == 'unit') {
                        continue;
                    }
                    $dwtzqk_hj[$k1] += $v1;
                }
                $v['hj'] = $v['gqbj'] + $v['ysgx'] + $v['zq'] + $v['yszx'] + $v['lcsy'];
                $dwtzqk_hj['hj'] += $v['hj'];
                return $v;
            })->values()->all();
            array_push($data_dwtzqk, $dwtzqk_hj);
            return $data_dwtzqk;
        }

        if ($table_name == 'xjlbyg') {
            $data_xjlbyg = Xjlbyg::all()->toArray();
            return $data_xjlbyg;
        }

        if ($table_name == 'xjlbsj') {
            $data_xjlbsj = Xjlbsj::all()->toArray();
            return $data_xjlbsj;
        }

        if ($table_name == 'zbqk') {
            $data_zbqk = Zbqk::all()->toArray();
            $data_zbqk = collect($data_zbqk)->map(function ($v) {
                $v['xj'] = $v['zyzj']+$v['cqgqtz']+$v['gdzc'];
                return $v;
            })->values()->all();
            return $data_zbqk;
        }

        $class = 'App\Models\PCompanyCheck\\' . ucfirst($table_name);;
        $table = new $class();
        return $table->query()->select(['*'])->get()->toArray();
    }

    protected function srhzBuild($data)
    {
        $field_arr = [
            ['key' => 'dbfdw', 'name' => '营业收入', 'data' => []],
            ['key' => 'lx', 'name' => '利息', 'data' => []],
            ['key' => 'glf', 'name' => '管理费', 'data' => []],
            ['key' => 'fh', 'name' => '分红', 'data' => []],
            ['key' => 'tzly', 'name' => '投资收益', 'data' => []],
            ['key' => 'ssjl', 'name' => '税收奖励', 'data' => []],
            ['key' => 'gpdx', 'name' => '高抛低吸', 'data' => []],
            ['key' => 'zj', 'name' => '租金', 'data' => []]
        ];
        collect($data)->map(function ($v) use (&$field_arr) {
            foreach ($field_arr as $k1 => $v1) {
                array_push($field_arr[$k1]['data'], [
                    'fee' => $v[$v1['key']],
                    'year' => $v['year'],
                ]);
            }
        })->values()->all();
        //累计
        $field_lj = collect($data)->map(function ($v) {
            return [
                'fee' => $v['dbfdw'] + $v['lx'] + $v['glf'] + $v['fh'] + $v['tzly'] + $v['ssjl'] + $v['gpdx'] + $v['zj'],
                'year' => $v['year'],
            ];
        })->values()->all();
        array_push($field_arr, ['key' => 'lj', 'name' => '累计', 'data' => $field_lj]);

        //横的合计
        $field_arr = collect($field_arr)->map(function ($v) {
            $total = collect($v['data'])->sum('fee');
            array_push($v['data'], ['fee' => $total, 'year' => 'hj']);
            return $v;
        })->values()->all();

        return $field_arr;
    }

    /**
     * @param $lnzc_data
     * @param $field_arr
     * @param $name
     */
    protected function lnzcData($field_arr)
    {
        $data = [];
        foreach ($field_arr as $k => $v) {
            $data1 = Lnzc::select(["{$v[0]} as fee", 'year'])->orderBy('year', 'desc')->get()->toArray();
            $data_temp1 = [
                'name' => $v[1],
                'data' => $data1,
            ];
            array_push($data, $data_temp1);
        }
        $hj = 0;
        $data = collect($data)->map(function ($v) use (&$hj) {
            $fee_tatal = collect($v['data'])->sum('fee');
            $hj += $fee_tatal;
            array_push($v['data'], ['year' => 'hj', 'fee' => $fee_tatal]);
            return $v;
        })->values()->all();

        return [$hj, $data];
    }
}
