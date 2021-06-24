<?php


namespace App\Services\Dp;


use App\Models\ImportData\Dkzlfx;
use App\Models\ImportData\Dqtx;
use App\Models\ImportData\Fyztqk;
use App\Models\ImportData\Jyzl;
use App\Models\ImportData\Zjbl;
use App\Models\ImportData\Zqzch;
use App\Services\ApiResponse;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DataService
{
    use ApiResponse;

    //月
    protected $m;

    protected $sub_m;

    //季
    protected $q;

    protected $sub_q;

    //年
    protected $y;

    protected $sub_y;

    public function __construct()
    {
        $this->m = Carbon::now()->month;
        $this->q = Carbon::now()->firstOfQuarter()->month;
        $this->y = Carbon::now()->year;
        $this->sub_m = Carbon::now()->subMonth()->month;
        $this->sub_q = Carbon::now()->subQuarter()->firstOfQuarter()->month;
        $this->sub_y = Carbon::now()->subYear()->year;
    }

    /**
     * 经营质量主数据
     * @param $type
     * @return array
     */
    public function jyzlMainData($type){
        switch ($type){
            case 'm':
                $rs = Jyzl::where('year', $this->y)
                    ->where('month', $this->m)
                    ->select(['ljtf', 'yue', 'sxsr', 'sxlr', 'zyzjzb'])
                    ->first();
                break;
            case 'q':
                $rs = Jyzl::where('year', $this->y)
                    ->where('month', '>=', $this->q)
                    ->selectRaw('sum(ljtf) as ljtf, sum(yue) as yue, sum(sxsr) as sxsr, sum(sxlr) as sxlr, avg(zyzjzb) as zyzjzb')
                    ->first();
                break;
            case 'y':
                $rs = Jyzl::where('year', $this->y)
                    ->where('month', '<=', $this->q)
                    ->selectRaw('sum(ljtf) as ljtf, sum(yue) as yue, sum(sxsr) as sxsr, sum(sxlr) as sxlr, avg(zyzjzb) as zyzjzb')
                    ->first();
                break;
        }
        return $rs?$rs->toArray():[];
    }

    /**
     * 经营质量投放量数据
     * @param $type
     * @return array
     */
    public function jyzlTflData($type){
        switch ($type){
            case 'm':
                $rs = Jyzl::where('year', $this->y)
                    ->select(['year', 'month', 'tfl'])
                    ->orderBy('month', 'asc')
                    ->get();
                break;
            case 'q':
                $q_arr = [
                    [1,3],
                    [4,6],
                    [7,9],
                    [10,12],
                ];
                $per_q_data = [];
                foreach ($q_arr as $v){
                    $q1 = Jyzl::where('year', $this->y)
                        ->where('month', '>=', $v[0])
                        ->where('month', '<=', $v[1])
                        ->selectRaw('sum(tfl) as tfl')
                        ->first();
                    array_push($per_q_data, [
                        'quarter' => implode('~', $v),
                        'tfl' => $q1?($q1->tfl==null?0:$q1->tfl):0,
                    ]);
                }
                return $per_q_data;
                break;
            case 'y':
                $rs = Jyzl::where('year', $this->y)
                    ->where('month', '<=', $this->q)
                    ->selectRaw('year, sum(tfl) as tfl')
                    ->groupBy('year')
                    ->get();
                break;
        }
        return $rs?$rs->toArray():[];
    }

    /**
     * 房押总体情况
     * @param $area_id
     * @param $type
     */
    public function fyztqkData($area_id, $type){
        $main_data = $this->getFyztqkMainData($area_id, $type);
        $sub_main_data = $this->getSubFyztqkMainData($area_id, $type);
        $tfl_data = $this->getFyztqkTflData($area_id, $type);
        $main_data['tfl_data'] = $tfl_data;
        $main_data['tfbs_compare_with_sub'] = ($main_data?$main_data['tfbs']:0) - ($sub_main_data?$sub_main_data['tfbs']:0);
        return $main_data;
    }

    protected function getFyztqkMainData($area_id, $type){
        $main_data = Fyztqk::when($area_id!=0, function ($query) use ($area_id){
            $query->where('area_id', $area_id);
        })
            ->where(function ($query) use ($type){
                switch ($type){
                    case 'm':
                        $query->where('month', $this->m);
                        break;
                    case 'q':
                        $query->where('month', '>=', $this->q);
                        break;
                    case 'y':
                        $query->where('year', $this->y);
                        break;
                }
            })
            ->selectRaw('sum(lx75_1) as lx75_1, sum(lx75_2) as lx75_2, sum(lx7_6) as lx7_6,
                    sum(lx7_7) as lx7_7, sum(pjpgj) as pjpgj, sum(hjs) as hjs, sum(hkbj) as hkbj,
                    avg(zhdyl) as zhdyl, avg(yybl) as yybl, avg(eybl) as eybl, sum(tfbs) as tfbs')
            ->first();
        return $main_data?$main_data->toArray():[];
    }

    protected function getSubFyztqkMainData($area_id, $type){
        $main_data = Fyztqk::when($area_id!=0, function ($query) use ($area_id){
            $query->where('area_id', $area_id);
        })
            ->where(function ($query) use ($type){
                switch ($type){
                    case 'm':
                        $query->where('month', $this->sub_m);
                        break;
                    case 'q':
                        $query->where('month', '>=', $this->sub_q);
                        $query->where('month', '<=', $this->q);
                        break;
                    case 'y':
                        $query->where('year', $this->sub_y);
                        break;
                }
            })
            ->selectRaw('sum(lx75_1) as lx75_1, sum(lx75_2) as lx75_2, sum(lx7_6) as lx7_6,
                    sum(lx7_7) as lx7_7, sum(pjpgj) as pjpgj, sum(hjs) as hjs, sum(hkbj) as hkbj,
                    avg(zhdyl) as zhdyl, avg(yybl) as yybl, avg(eybl) as eybl, sum(tfbs) as tfbs')
            ->first();
        return $main_data?$main_data->toArray():[];
    }

    /**
     * 房押整体情况
     * @param $area_id
     * @param $type
     * @return array
     */
    protected function getFyztqkTflData($area_id, $type){
        switch ($type){
            case 'm':
                $rs = Fyztqk::when($area_id!=0, function ($query) use ($area_id){
                    $query->where('area_id', $area_id);
                })
                    ->selectRaw('month, sum(tfl) as tfl')
                    ->groupBy('month')
                    ->get();
                return $rs?$rs->toArray():[];
                break;
            case 'q':
                $q_arr = [
                    [1,3],
                    [4,6],
                    [7,9],
                    [10,12],
                ];
                $per_q_data = [];
                foreach ($q_arr as $v){
                    $q = Fyztqk::when($area_id!=0, function ($query) use ($area_id){
                        $query->where('area_id', $area_id);
                    })
                        ->where('month', '>=', $v[0])
                        ->where('month', '<=', $v[1])
                        ->selectRaw('sum(tfl) as tfl')
                        ->first();
                    array_push($per_q_data, [
                        'quarter' => implode('~', $v),
                        'tfl' => $q?($q->tfl==null?0:$q->tfl):0,
                    ]);
                }
                return $per_q_data;
                break;
            case 'y':
                $rs = Fyztqk::when($area_id!=0, function ($query) use ($area_id){
                    $query->where('area_id', $area_id);
                })
                    ->selectRaw('year, sum(tfl) as tfl')
                    ->groupBy('year')
                    ->get();
                return $rs?$rs->toArray():[];
                break;
        }
    }

    /**
     * @param $type
     * @param $area_id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function dkzlfxData($type, $area_id){
        $form_data = $this->getDkzlfxForm($type, $area_id);
        return $form_data;
    }

    /**
     * @param $type
     * @return mixed
     */
    protected function getDkzlfxForm($type, $area_id){
        $total_data = Dkzlfx::where('type1', $type)
            ->when($area_id!=0, function ($query) use ($area_id){
                $query->where('area_id', $area_id);
            })
            ->selectRaw('sum(tfbs) as ztfbs, sum(fkje) as zfkje')
            ->first();
        $total_data = $total_data?$total_data->toArray():[];
        $data =  Dkzlfx::where('type1', $type)
            ->when($area_id!=0, function ($query) use ($area_id){
                $query->where('area_id', $area_id);
            })
            ->selectRaw('type2, min(type1_name) as type1_name, min(type2_name) as type2_name,  sum(tfbs) as tfbs, sum(fkje) as fkje, sum(eybl) as eybl')
            ->groupBy('type2')
            ->orderBy('type2', 'asc')
            ->get()->toArray();
        foreach ($data as &$v){
            $v['ztfbs'] = $total_data?$total_data['ztfbs']:0;
            $v['zfkje'] = $total_data?$total_data['zfkje']:0;
        }
        return $data;
    }


    /**
     * 到期提醒
     * @return int[]
     */
    public function dqtxData(){
        $rs = Dqtx::selectRaw('sum(wdqbs) as wdqbs, sum(wdqje) as wdqje')
            ->first();
        return $rs?$rs->toArray():[
            'wdqbs' => 0,
            'wdqje' => 0,
        ];
    }


    /**
     * 资金保理
     * @return array
     */
    public function zjblData(){
        $month_data = Zjbl::where('year', $this->y)
            ->where('month', $this->m)
                ->select(['year', 'month', 'ypjlr', 'zpjlr', 'dysr', 'zsr'])
            ->first();
        $month_data = $month_data?$month_data->toArray():[
            'year' => $this->y,
            'month' => $this->m,
            'ypjlr' =>0,
            'zpjlr' => 0,
            'dysr' => 0,
            'zsr' => 0,
        ];
        $tfl_data = Zjbl::where('year', $this->y)
            ->selectRaw('month, sum(tfl) as tfl')
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get()->toArray();
        $month_data['tfl_data'] = $tfl_data;
        return $month_data;
    }

    /**
     * 证券资产化
     * @return mixed
     */
    public function zqzchData(){
        return  Zqzch::select(['zgm', 'xmmc', 'hxqy', 'glgm', 'll', 'fxbs', 'jhglr', 'jycs'])
            ->orderBy('zgm', 'desc')
            ->limit(5)
            ->offset(0)
            ->get()->toArray();
    }
}
