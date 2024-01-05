<?php

namespace App;

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

    public function postTask($queue, $task, $args)
    {
        $this->channel->queue_declare($queue, false, true, false, false);
        $data = json_encode([
            'id' => uniqid(),
            'task' => $task,
            'args' => $args,
            'retries' => 0,
        ]);

        $msg = new AMQPMessage($data, [
            'content_type' => 'application/json',
            'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT
        ]);

        $this->channel->basic_publish($msg, '', $queue);
    }

    public function close()
    {
        $this->channel->close();
        $this->connection->close();
    }

}
