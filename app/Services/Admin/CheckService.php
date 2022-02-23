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
        $field_arr = ['dbfdw', 'lx', 'glf', 'fh',
            'tzly', 'ssjl', 'gpdx', 'zj'
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
                    Srhz::updateOrCreate(['year' => $year], [
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
    protected function xjlbsj($data){
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

    protected function xjlbyg($data){
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
     * @return mixeda
     */
    public function show($table_name){
        if($table_name == 'fhmx'){
            $fhmx_data = [];
            $company = Fhmx::distinct()->pluck('company')->toArray();
            foreach ($company as $k => $v){
                $data = Fhmx::where('company', $v)->select(['*'])->orderBy('year', 'desc')->get()->toArray();
                $data_temp = [];
                $data_temp['unit'] = $data[0]['unit'];
                $data_temp['company'] = $data[0]['company'];
                foreach ($data as $v){
                    $data_temp[$v['year']] = $v['fee'];
                }
                $data_temp['remark'] = $data[0]['remark'];
                array_push($fhmx_data, $data_temp);
            }
            return $fhmx_data;
        }

        $class = 'App\Models\PCompanyCheck\\'.ucfirst($table_name);;
        $table = new $class();
        return $table->query()->select(['*'])->get()->toArray();
    }
}
