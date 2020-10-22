<?php

namespace Service;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class Worker extends Service
{
    private $logger;

    public function __construct()
    {
        parent::__construct();
        $this->logger = new Logger('worker_logger');
        $this->logger->pushHandler(new StreamHandler(__DIR__.'/events.log'));
    }

    public function work()
    {
        $callback = function ($msg) {
            sleep(1);
            $this->logger->info($msg->body);
            $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
        };

        $this->channel->basic_qos(null, 1, null);
        $this->channel->basic_consume('event_queue', '', false, false, false, false, $callback);

        while ($this->channel->is_consuming()) {
            $this->channel->wait();
        }

        $this->channel->close();
        $this->connection->close();
    }
}

