<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Task;
use App\Notifications\TaskDeadlineReminder;
use Illuminate\Support\Carbon;

class SendDeadlineReminders implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $now = Carbon::now();
        $in24Hours = Carbon::now()->addDay();

        $tasks = Task::whereBetween('due_date', [$now, $in24Hours])
                     ->with('assignees')
                     ->get();

        foreach ($tasks as $task) {
            foreach ($task->assignees as $user) {
                $user->notify(new TaskDeadlineReminder($task));
            }
        }
    }
}
