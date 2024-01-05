<?php

namespace App\Console\Commands;

use App\Celery;
use Illuminate\Console\Command;

class TestCommand extends Command
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

        $queue = 'celery';
        $task = 'tasks.add';
        $args = [4, 4];

        $celery->postTask($queue, $task, $args);
        $celery->close();
    }
}
