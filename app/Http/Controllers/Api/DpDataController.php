<?php


namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Services\Dp\DataService;
use Illuminate\Http\Request;

class DpDataController extends Controller
{
    protected $data_service;

    public function __construct(DataService $ds)
    {
        $this->data_service = $ds;
    }

    /**
     * 经营质量主数据
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function getJyzlMainData(Request $request){
        $validated = $this->validate($request, ['type' => 'in:m,q,y']);
        $data = $this->data_service->jyzlMainData($validated['type']??'m');
        return $this->success($data);
    }

    /**
     * 经营质量投放量数据
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function getJyzlTflData(Request $request){
        $validated = $this->validate($request, ['type' => 'in:m,q,y']);
        $data = $this->data_service->jyzlTflData($validated['type']??'m');
        return $this->success($data);
    }

    /**
     * 房押整体情况
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function getFyztqkData(Request $request){
        $validated = $this->validate($request, ['area_id' => 'in:0,1,2','type' => 'in:m,q,y']);
        $data = $this->data_service->fyztqkData($validated['area_id']??0, $validated['type']??'m');
        return $this->success($data);
    }

    /**
     * 贷款质量分析
     * @param Request $request
     * @throws \Illuminate\Validation\ValidationException
     */
    public function getDkzlFx(Request $request){
        $validated = $this->validate($request, ['type' => 'in:1,2,3,4,5', 'area_id' => 'in:0,1,2']);
        $data = $this->data_service->dkzlfxData($validated['type']??1, $validated['area_id']??0);
        return $this->success($data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function getDqtx(Request $request){
        $data = $this->data_service->dqtxData();
        return $this->success($data);
    }

    /**
     * 资金保理
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function getZjbl(Request $request){
        $data = $this->data_service->zjblData();
        return $this->success($data);
    }


    /**
     * 证券资产化
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function getZqzch(){
        $data = $this->data_service->zqzchData();
        return $this->success($data);
    }
}
