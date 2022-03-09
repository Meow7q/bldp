<?php

namespace App\Console\Commands;

use ExcelMerge\ExcelMerge;
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
        $files = array(public_path('/static/template/lnzc.xlsx'), public_path('/static/template/zbqk.xlsx'));

        $merged = new ExcelMerge($files);
        $merged->download(public_path("/static/template/aaa.xlsm"));
    }
}
