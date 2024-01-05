<?php

namespace App;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Redis;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class Celery
{
    private $connection;
    private $channel;

    public function __construct($host, $port, $username, $password, $vhost = '/')
    {
        $this->connection = new AMQPStreamConnection($host, $port, $username, $password, $vhost);
        $this->channel = $this->connection->channel();
    }

    /**
     * @param $queue
     * @param $task
     * @param $args
     * @return string $task_id;
     */
    public function postTask($queue, $task, $args)
    {
        $id = uniqid();
        $this->channel->queue_declare($queue, false, true, false, false);
        $data = json_encode([
            'id' => $id,
            'task' => $task,
            'args' => $args,
            'retries' => 0,
        ]);

        $msg = new AMQPMessage($data, [
            'content_type' => 'application/json',
            'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT
        ]);

        $this->channel->basic_publish($msg, '', $queue);

        return $id;
    }

    /**
     * @param $taskId
     * @return JsonResponse
     */
    public function getTaskResult($taskId)
    {
        // 假设任务 ID 是从请求参数中传入
        $result = Redis::get('celery-task-meta-' . $taskId);

        if ($result) {
            // 如果结果存在，那么解码 JSON 结构并返回结果
            $resultArray = json_decode($result, true);
            return response()->json($resultArray);
        } else {
            // 如果结果不存在，返回任务未完成的响应
            return response()->json(['error' => 'Task not completed yet'], 404);
        }
    }

    public function close()
    {
        $this->channel->close();
        $this->connection->close();
    }

}
