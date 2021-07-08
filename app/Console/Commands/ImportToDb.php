<?php

namespace App\Console\Commands;

use App\Enum\AuditStatus;
use App\Enum\ImportStatus;
use App\Models\ImportData\FileBldp;
use App\Services\Dp\DataService;
use App\Services\ExcelService;
use Illuminate\Console\Command;

class ImportToDb extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:import-to-db';

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
        $list = FileBldp::where('audit_status', AuditStatus::STATUS_PASS)
            ->where('import_status', ImportStatus::STATUS_WAITING)
            ->get()->toArray();
        foreach ($list as $k => $v){
            (new ExcelService())->import($v['id'], $v['year'], $v['month'], $v['file_url']);
        }
    }
}
