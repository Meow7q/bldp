<?php

namespace App\Console\Commands;

use App\Services\ExcelService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

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
        //
        Log::channel('test')->info(time());
        (new ExcelService())->import(1, 2021, '/upload/2021-06-25/74a31ddc4101d35ed1f64ea399dbb02b.xlsx');
    }
}
