<?php

namespace App\Console\Commands;

use App\Models\PCompanyCheck\Dwtzqk;
use App\Models\PCompanyCheck\FhmxNew;
use App\Models\PCompanyCheck\KjbbNew;
use App\Models\PCompanyCheck\LnzcNew;
use App\Models\PCompanyCheck\SrhzNew;
use App\Models\PCompanyCheck\TableList;
use App\Models\PCompanyCheck\Xjlbsj;
use App\Models\PCompanyCheck\Xjlbyg;
use App\Models\PCompanyCheck\Ysbmb;
use App\Models\PCompanyCheck\Ysjlcb;
use App\Models\PCompanyCheck\Zbqk;
use App\Models\User;
use Carbon\Carbon;
use ExcelMerge\ExcelMerge;
use Illuminate\Console\Command;
use Rap2hpoutre\FastExcel\FastExcel;
use Rap2hpoutre\FastExcel\SheetCollection;

class Test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->initUser();
    }

    protected function initUser(){
        $users = [
            ['username' => '王露红', 'usercode' => '1831268', 'password' =>'123456', 'permission' => 1],
            ['username' => '杨颖', 'usercode' => '10009405', 'password' =>'123456', 'permission' => 1],
            ['username' => '张瑜斐', 'usercode' => '10003384', 'password' =>'123456', 'permission' => 1],
            ['username' => '沈莉莉', 'usercode' => '2038987', 'password' =>'123456', 'permission' => 1],
            ['username' => '陈龚花', 'usercode' => '1002180', 'password' =>'123456', 'permission' => 1],
            ['username' => '顾佳璐', 'usercode' => '1702389', 'password' =>'123456', 'permission' => 1],

            ['username' => '陈锦石', 'usercode' => '1000001', 'password' =>'123456', 'permission' => 2],
            ['username' => '孟小军', 'usercode' => '1006329', 'password' =>'123456', 'permission' => 2],
            ['username' => '孟东阳', 'usercode' => '2039630', 'password' =>'123456', 'permission' => 2],
            ['username' => '郑伟', 'usercode' => '10008529', 'password' =>'123456', 'permission' => 2],
            ['username' => '江翔', 'usercode' => '10002438', 'password' =>'123456', 'permission' => 2],


            ['username' => '游客', 'usercode' => '999999', 'password' =>'123456', 'permission' => 2],
        ];
        foreach ($users as $user){
            User::firstOrCreate(['usercode' => $user['usercode']],[
                'username' => $user['username'],
                'usercode' => $user['usercode'],
                'password' => $user['password'],
                'permission' => $user['permission'],
            ]);
        }
    }

    protected function truncateData(){
        LnzcNew::truncate();
        Zbqk::truncate();
        KjbbNew::truncate();
        SrhzNew::truncate();
        FhmxNew::truncate();
        Ysbmb::truncate();
        Ysjlcb::truncate();
        Dwtzqk::truncate();
        Xjlbsj::truncate();
        Xjlbyg::truncate();
    }
}
