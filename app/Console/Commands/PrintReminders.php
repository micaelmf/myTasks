<?php

namespace App\Console\Commands;

use App\Events\TaskOverdue;
use App\Models\Reminder;
use Carbon\Carbon;
use Illuminate\Console\Command;

class PrintReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:print-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Print reminders that are due';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();
        $reminders = Reminder::where('status', '!=', 'doing')
            ->where('status', '!=', 'archived')
            ->where('reminder_time', '<', $now)
            ->get();

        foreach ($reminders as $reminder) {
            sleep(2);
            event(new TaskOverdue($reminder));
            $this->info($reminder);
        }

        return 0;
    }
}
