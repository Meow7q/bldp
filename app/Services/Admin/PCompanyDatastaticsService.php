<?php


namespace App\Services\Admin;


use App\Models\PCompanyCheck\Lnzc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpWord\TemplateProcessor;

class PCompanyDatastaticsService
{
    public function saveToWord(){
        $tmp = new TemplateProcessor(public_path('/static/template/点检表.docx'));
        $tmp->setValues([
            'fee_kg' => 2000
        ]);
        $tmp->saveAs(public_path('/static/template/母公司经营管理指标成果点检表.docx'));
    }


    /**
     * 历年资产详情
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function statisticsLnzcxq(){
        $fee_kg = Lnzc::select(DB::raw("SUM(yxwzc)+SUM(cwfy)+SUM(gz)+SUM(pgzxf)+SUM(zj)+SUM(bgf)+SUM(ywzdf)+SUM(clf)+SUM(qtywcb)+SUM(kgqt) as fee"))
            ->first();
        $fee_ct = Lnzc::select(DB::raw("SUM(ggsjf)+SUM(sds)+SUM(ctqt) as fee"))
            ->first();
        return [
            'fee_kg' => $fee_kg->fee,
            'fee_ct' => $fee_ct->fee,
            'fee_total' => $fee_kg->fee + $fee_ct->fee,
        ];
    }
}
