<?php

namespace App\Console\Commands;

use App\Celery;
use Illuminate\Console\Command;

class CheckTaskStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'task:status {task_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks the status of a Celery task';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $host = env('RABBITMQ_HOST');
        $port = 5672;
        $username = env('RABBITMQ_DEFAULT_USER');
        $pass = env('RABBITMQ_DEFAULT_PASS');
        $vhost = '/';

        $celery = new Celery($host, $port, $username, $pass, $vhost);

        $taskId = $this->argument('task_id');
        $result = $celery->getTaskResult($taskId);

        if ($result) {
            $this->info('Task result:');
            $this->line($result);
        } else {
            $this->error('Task result not found for task ID: ' . $taskId);
        }
    }
}
