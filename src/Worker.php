<?php

namespace Service;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

/**
 * Class Worker
 * @package Service
 */
class Worker extends RabbitMQService
{
    private $logger;

    /**
     * Кроме подключения к RabbitMQ тут инициируется логгер, чтобы потом логировать обработанные события
     */
    public function __construct()
    {
        parent::__construct();
        $this->logger = new Logger('worker_logger');
        $this->logger->pushHandler(new StreamHandler(__DIR__.'/../events.log'));
    }

    /**
     * Принимает и обрабатывает сообщения из очереди
     * @throws \ErrorException
     */
    public function work()
    {
        $callback = function ($msg) {
            $this->logger->info($msg->body);
            sleep(1);
            $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
        };

        $this->channel->basic_qos(null, 1, null);
        $this->channel->basic_consume(self::QUEUE_NAME, '', false, false, false, false, $callback);

        while ($this->channel->is_consuming()) {
            $this->channel->wait();
        }

        $this->channel->close();
        $this->connection->close();
    }
}

