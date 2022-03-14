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
            ['name' => '王露红', 'usercode' => '1831268', 'password' =>'123456', 'permission' => 1],
            ['name' => '杨颖', 'usercode' => '10009405', 'password' =>'123456', 'permission' => 1],
            ['name' => '张瑜斐', 'usercode' => '10003384', 'password' =>'123456', 'permission' => 1],
            ['name' => '江翔', 'usercode' => '10002438', 'password' =>'123456', 'permission' => 2],
            ['name' => '沈莉莉', 'usercode' => '2038987', 'password' =>'123456', 'permission' => 1],
        ];
        foreach ($users as $user){
            User::firstOrCreate(['usercode' => $user['usercode']],[
                'name' => $user['name'],
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
