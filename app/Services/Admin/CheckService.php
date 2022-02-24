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
        switch ($table_name) {
            case 'lnzc':
                $this->lnzc($collection);
                break;
            case 'zbqk':
                $this->zbqk($collection);
                break;
            case 'kjbb':
                $this->kjbb($collection);
                break;
            case 'srhz':
                $this->srhz($collection);
                break;
            case 'fhmx':
                $this->fhmx($collection);
                break;
            case 'ysbmb':
                $this->ysbmb($collection);
                break;
            case 'ysjlcb':
                $this->ysjlcb($collection);
                break;
            case 'dwtzqk':
                $this->dwtzqk($collection);
                break;
            case 'xjlbsj':
                $this->xjlbsj($collection);
                break;
            case 'xjlbyg':
                $this->xjlbyg($collection);
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
                    $filed_name => empty($v) ? 0 : $v
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
                    'rzbj' => $line[1],
                    'rzpjcb' => $line[2],
                    'zycyzj' => $line[3],
                    'hjtr' => $line[4],
                    'lnfyzc' => $line[5],
                    'zyzj' => $line[6],
                    'cqgqtz' => $line[7],
                    'gdzc' => $line[8],
                    'zmjzc' => $line[9],
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
                        $field_arr[$k1] => $v
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
                        $field_arr[$k1] => $v
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
                        'fee' => $fee,
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
                    Ysbmb::create([
                        'type' => array_shift($line),
                        'fee_d' => array_shift($line),
                        'fee_e' => array_shift($line),
                        'fee_f' => array_shift($line),
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
                    Ysjlcb::create([
                        'name' => array_shift($line),
                        'fee_d' => array_shift($line),
                        'fee_e' => array_shift($line),
                        'fee_f' => array_shift($line),
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
                Dwtzqk::create([
                    'unit' => array_shift($line),
                    'gqbj' => array_shift($line),
                    'ysgx' => array_shift($line),
                    'zq' => array_shift($line),
                    'yszx' => array_shift($line),
                    'lcsy' => array_shift($line),
                    'gxzj' => array_shift($line),
                    'yfhhysj' => array_shift($line),
                    'tzhbl' => array_shift($line),
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
                Xjlbsj::create([
                    'name' => array_shift($line),
                    'fee_kg' => array_shift($line),
                    'fee_ct' => array_shift($line),
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
                Xjlbyg::create([
                    'project' => array_shift($line),
                    'fee_1' => array_shift($line),
                    'fee_2' => array_shift($line),
                    'fee_3' => array_shift($line),
                    'fee_4' => array_shift($line),
                    'fee_5' => array_shift($line),
                    'fee_6' => array_shift($line),
                    'fee_7' => array_shift($line),
                    'fee_8' => array_shift($line),
                    'fee_9' => array_shift($line),
                    'fee_10' => array_shift($line),
                    'fee_11' => array_shift($line),
                    'fee_12' => array_shift($line),
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
                foreach ($data as $v) {
                    $data_temp[$v['year']] = $v['fee'];
                }
                $data_temp['remark'] = $data[0]['remark'];
                array_push($fhmx_data, $data_temp);
            }
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
            array_push($lnzc_data, ['hj' => $hj1 +$hj2]);
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
        $data = collect($data)->map(function ($v) use (&$hj){
            $fee_tatal = collect($v['data'])->sum('fee');
            $hj += $fee_tatal;
            array_push($v['data'], ['year' => 'hj', 'fee' => $fee_tatal]);
            return $v;
        })->values()->all();

        return [$hj, $data];
    }


    public function dataStatistics($type)
    {
        switch ($type) {
            case 'lnzcxq':
                return $this->getLjzcxq();
                break;
        }
    }

    protected function getLjzcxq()
    {
        $field_kg = ['yxwzc', 'cwfy', 'gz', 'pgzxf', 'zj', 'bgf', 'ywzdf', 'clf', 'qtywcb', 'kgqt'];
        $field_ct = ['ggsjf', 'sds', 'ctqt'];

    }
}
