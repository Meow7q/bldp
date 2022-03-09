<?php

namespace App\Console\Commands;

use App\Models\PCompanyCheck\FhmxNew;
use App\Models\PCompanyCheck\LnzcNew;
use App\Models\PCompanyCheck\TableList;
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

    }
}
