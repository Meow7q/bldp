<?php

namespace App\Console\Commands;

use App\Models\PCompanyCheck\Dwtzqk;
use App\Models\PCompanyCheck\FhmxNew;
use App\Models\PCompanyCheck\KjbbNew;
use App\Models\PCompanyCheck\LnzcNew;
use App\Models\PCompanyCheck\SrhzNew;
use App\Models\PCompanyCheck\Xjlbsj;
use App\Models\PCompanyCheck\Xjlbyg;
use App\Models\PCompanyCheck\Ysbmb;
use App\Models\PCompanyCheck\Ysjlcb;
use App\Models\PCompanyCheck\Zbqk;
use App\Services\Admin\PCompanyDatastaticsService;
use Illuminate\Console\Command;

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
