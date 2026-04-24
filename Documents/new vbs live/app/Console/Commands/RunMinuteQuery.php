<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\DB;
class RunMinuteQuery extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:run-minute-query';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
     public function handle(): int
    {
    DB::connection('sqlsrv')
    ->table('vbs_db.dbo.request_details')
    ->whereRaw("CONVERT(char(16), departure_time, 120) = CONVERT(char(16), GETDATE(), 120)")
    ->update(['status' => 'departed', 'updated_at' => now()]);
        
    
        $this->info('Minute‑query ran at '.now());
        return Command::SUCCESS;
    }
}
