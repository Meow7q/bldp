<?php

namespace App\Console\Commands;

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
        dd(0.8345+(-1));
        //
        //Log::channel('test')->info(time());
       // (new ExcelService())->import(4, 2020, 12, '/static/2020-12.xlsx');
        (new PCompanyDatastaticsService())->saveToWord();
    }
}
