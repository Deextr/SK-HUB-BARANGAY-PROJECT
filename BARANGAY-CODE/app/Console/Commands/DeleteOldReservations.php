<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DeleteOldReservations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reservations:delete-old';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete reservations older than 6 months';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $sixMonthsAgo = now()->subMonths(8);

        $deletedCount = \App\Models\Reservation::where('created_at', '<', $sixMonthsAgo)->delete();

        $this->info("Successfully deleted {$deletedCount} old reservations.");

        return 0;
    }
}
