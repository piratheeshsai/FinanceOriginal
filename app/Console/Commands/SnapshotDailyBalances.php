<?php

namespace App\Console\Commands;

use App\Models\Account;
use App\Models\Branch;
use App\Models\DailyBalance;
use Illuminate\Console\Command;

class SnapshotDailyBalances extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:snapshot-daily-balances';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {




        $date = now()->format('Y-m-d');

    // Loop through each branch
    Branch::each(function ($branch) use ($date) {
        DailyBalance::updateOrCreate(
            [
                'branch_id' => $branch->id,
                'date' => $date,
            ],
            [
                'cashier_balance' => Account::where('type', 'cashier')
                    ->where('branch_id', $branch->id)
                    ->value('balance'),
                'interest_balance' => Account::where('type', 'interest')
                    ->where('branch_id', $branch->id)
                    ->value('balance'),
                'capital_balance' => Account::where('type', 'capital')
                    ->where('branch_id', $branch->id)
                    ->value('balance'),
            ]
        );
    });





    }
}
