<?php


namespace App\Services\Admin;

use App\Models\PCompanyCheck\Dwtzqk;
use App\Models\PCompanyCheck\Fhmx;
use App\Models\PCompanyCheck\FhmxNew;
use App\Models\PCompanyCheck\Kjbb;
use App\Models\PCompanyCheck\Lnzc;
use App\Models\PCompanyCheck\Srhz;
use App\Models\PCompanyCheck\SrhzNew;
use App\Models\PCompanyCheck\TableList;
use App\Models\PCompanyCheck\Xjlbsj;
use App\Models\PCompanyCheck\Xjlbyg;
use App\Models\PCompanyCheck\Ysbmb;
use App\Models\PCompanyCheck\Ysjlcb;
use App\Models\PCompanyCheck\Zbqk;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
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
                if($line_count != 3){
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
                if($line_count != 27){
                    throw new \Exception('模版错误');
                }
                $this->srhz($collection);
                $this->data_service->statisticsSrqk();
                break;
            case 'fhmx':
                if($line_count != 10){
                    throw new \Exception('模版错误');
                }
                $this->fhmx($collection);
                $this->data_service->statisticsFhqk();
                break;
            case 'ysbmb':
                if($line_count != 20){
                    throw new \Exception('模版错误');
                }
                $this->ysbmb($collection);
                $this->data_service->statisticsBmysqk();
                break;
            case 'ysjlcb':
                if($line_count != 16){
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
                    $filed_name => is_numeric($v) ? $v : 0
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
                    'xj' => is_numeric($line[9]) ? $line[9] : 0,
                    'zmjzc' => is_numeric($line[10]) ? $line[10] : 0,
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
        try {
            DB::beginTransaction();
            SrhzNew::truncate();
            foreach ($data as $k1 => $line) {
                $unit = array_shift($line);
                $type = array_shift($line);
                $hz = array_shift($line);
                $fee_2022 = array_shift($line);
                $fee_2021 = array_shift($line);
                $fee_2020 = array_shift($line);
                $fee_2019 = array_shift($line);
                $fee_2018 = array_shift($line);
                $fee_2017 = array_shift($line);
                $fee_2016 = array_shift($line);
                $fee_2015 = array_shift($line);
                $fee_2014 = array_shift($line);
                $fee_2013 = array_shift($line);
                SrhzNew::create([
                    'unit' => $k1<9?'合计':($k1<18?'控股':'城投'),
                    'type' => $type,
                    'hz' => is_numeric($hz)?$hz:0,
                    'fee_2022' => is_numeric($fee_2022) ? $fee_2022 : 0,
                    'fee_2021' => is_numeric($fee_2021) ? $fee_2021 : 0,
                    'fee_2020' => is_numeric($fee_2020) ? $fee_2020 : 0,
                    'fee_2019' => is_numeric($fee_2019) ? $fee_2019 : 0,
                    'fee_2018' => is_numeric($fee_2018) ? $fee_2018 : 0,
                    'fee_2017' => is_numeric($fee_2017) ? $fee_2017 : 0,
                    'fee_2016' => is_numeric($fee_2016) ? $fee_2016 : 0,
                    'fee_2015' => is_numeric($fee_2015) ? $fee_2015 : 0,
                    'fee_2014' => is_numeric($fee_2014) ? $fee_2014 : 0,
                    'fee_2013' => is_numeric($fee_2013) ? $fee_2013 : 0,
                ]);
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
        try {
            DB::beginTransaction();
            FhmxNew::truncate();
            foreach ($data as $k1 => $line) {
                $unit = array_shift($line);
                $name = array_shift($line);
                $hj = array_shift($line);
                $fee_2022 = array_shift($line);
                $fee_2021 = array_shift($line);
                $fee_2020 = array_shift($line);
                $fee_2019 = array_shift($line);
                $fee_2018 = array_shift($line);
                $fee_2017 = array_shift($line);
                $fee_2016 = array_shift($line);
                $fee_2015 = array_shift($line);
                $fee_2014 = array_shift($line);
                $fee_2013 = array_shift($line);
                $remark = array_pop($line);
                FhmxNew::updateOrCreate([
                    'unit' => (empty($unit)&&$name!='合计')?'中南控股':$unit,
                    'name' => $name,
                    'hj' => $hj,
                    'remark' => $remark,
                    'fee_2022' => is_numeric($fee_2022) ? $fee_2022 : 0,
                    'fee_2021' => is_numeric($fee_2021) ? $fee_2021 : 0,
                    'fee_2020' => is_numeric($fee_2020) ? $fee_2020 : 0,
                    'fee_2019' => is_numeric($fee_2019) ? $fee_2019 : 0,
                    'fee_2018' => is_numeric($fee_2018) ? $fee_2018 : 0,
                    'fee_2017' => is_numeric($fee_2017) ? $fee_2017 : 0,
                    'fee_2016' => is_numeric($fee_2016) ? $fee_2016 : 0,
                    'fee_2015' => is_numeric($fee_2015) ? $fee_2015 : 0,
                    'fee_2014' => is_numeric($fee_2014) ? $fee_2014 : 0,
                    'fee_2013' => is_numeric($fee_2013) ? $fee_2013 : 0,
                ]);
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
            $form_head = [];
            foreach ($data as $k1 => $line) {
                if($k1 == 0){
                    foreach ($line as $k2=>$v2){
                        array_push($form_head, $k2);
                    }
                }
                if ($k1 > 0) {
                    $type = array_shift($line);
                    $fee_d = array_shift($line);
                    $fee_e = array_shift($line);
                    $de = array_shift($line);
                    $fee_f = array_shift($line);
                    $df = array_shift($line);
                    Ysbmb::create([
                        'type' => $type,
                        'fee_d' => is_numeric($fee_d) ? $fee_d : 0,
                        'fee_e' => is_numeric($fee_e) ? $fee_e : 0,
                        'fee_f' => is_numeric($fee_f) ? $fee_f : 0,
                        'de' => is_numeric($de) ? $de : 0,
                        'df' => is_numeric($df) ? $df : 0,
                    ]);
                }
            }
            Redis::set('form_head_ysbmb', json_encode($form_head));
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
            $form_head = [];
            foreach ($data as $k1 => $line) {
                if($k1 == 0){
                    foreach ($line as $k2=>$v2){
                        array_push($form_head, $k2);
                    }
                }
                if ($k1 > 0) {
                    $name = array_shift($line);
                    $fee_d = array_shift($line);
                    $fee_e = array_shift($line);
                    $de = array_shift($line);
                    $fee_f = array_shift($line);
                    $df = array_shift($line);
                    Ysjlcb::create([
                        'name' => $name,
                        'fee_d' => is_numeric($fee_d) ? $fee_d : 0,
                        'fee_e' => is_numeric($fee_e) ? $fee_e : 0,
                        'fee_f' => is_numeric($fee_f) ? $fee_f : 0,
                        'de' => is_numeric($de) ? $de : 0,
                        'df' => is_numeric($df) ? $df : 0,
                    ]);
                }
            }
            Redis::set('form_head_ysjlcb', json_encode($form_head));
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
                $hj = array_shift($line);
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
                    'hj' => is_numeric($hj) ? $hj : 0,
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
            $form_head = [];
            foreach ($data as $k1 => $line) {
                if($k1 == 0){
                    foreach ($line as $k2=>$v2){
                        array_push($form_head, $k2);
                    }
                }
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
            Redis::set('form_head_xjlbsj', json_encode($form_head));
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
            return  FhmxNew::all()->toArray();
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
                $kgtb = $data[1]?(round(($data[0] - $data[1]) / $data[1], 2) * 100):0;
                $cttb = $data[3]?(round(($data[2] - $data[3]) / $data[3], 2) * 100):0;
                if($v[0] == 'qmwfplr' || $v[0] == 'dnjlr'){
                    $kgtb = -$kgtb;
                    $cttb = -$cttb;
                }
                return [
                    'name' => $v[1],
                    '控股2022' => $data[0],
                    '控股2021' => $data[1],
                    '控股同比' => $kgtb?$kgtb.'%':$kgtb,
                    '城投2022' => $data[2],
                    '城投2021' => $data[3],
                    '城投同比' => $cttb?$cttb.'%':$cttb,
                ];
            })->values()->all();
            return $kjbb_data;
        }

        if ($table_name == 'srhz') {
            return SrhzNew::all()->toArray();
        }

        if ($table_name == 'ysbmb') {
            $data_ysbmb = Ysbmb::all()->toArray();
            $form_head  = json_decode(Redis::get('form_head_ysbmb'), true);
            return [
                'form_head' => $form_head,
                'data' => $data_ysbmb
            ];
        }

        if ($table_name == 'ysjlcb') {
            $data_ysjlcb = Ysjlcb::all()->toArray();
            $form_head  = json_decode(Redis::get('form_head_ysjlcb'), true);
            return [
                'form_head' => $form_head,
                'data' => $data_ysjlcb
            ];
        }

        if ($table_name == 'dwtzqk') {
            $data_dwtzqk = Dwtzqk::all()->toArray();
            return $data_dwtzqk;
        }

        if ($table_name == 'xjlbyg') {
            $data_xjlbyg = Xjlbyg::all()->toArray();
            return $data_xjlbyg;
        }

        if ($table_name == 'xjlbsj') {
            $data_xjlbsj = Xjlbsj::all()->toArray();
            $form_head  = json_decode(Redis::get('form_head_xjlbsj'), true);
            return [
                'form_head' => $form_head,
                'data' => $data_xjlbsj
            ];
        }

        if ($table_name == 'zbqk') {
            $data_zbqk = Zbqk::all()->toArray();
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
