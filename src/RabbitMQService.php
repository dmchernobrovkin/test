<?php


namespace Service;

use PhpAmqpLib\Connection\AMQPStreamConnection;

abstract class RabbitMQService
{
    /**
     * Название очереди
     */
    const QUEUE_NAME = 'event_queue';

    /**
     * @var AMQPStreamConnection
     */
    protected $connection;

    /**
     * @var \PhpAmqpLib\Channel\AMQPChannel
     */
    protected $channel;

    /**
     * Подключение к очереди в RabbitMQ (общее для отправителя и получателя сообщений)
     */
    public function __construct()
    {
        $this->connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
        $this->channel = $this->connection->channel();

        $this->channel->queue_declare(self::QUEUE_NAME, false, true, false, false);
    }
}