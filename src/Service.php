<?php


namespace Service;

use PhpAmqpLib\Connection\AMQPStreamConnection;

abstract class Service
{
    const QUEUE_NAME = 'event_queue';

    protected $connection;
    protected $channel;

    /**
     * Service constructor.
     */
    public function __construct()
    {
        $this->connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
        $this->channel = $this->connection->channel();

        $this->channel->queue_declare(self::QUEUE_NAME, false, true, false, false);
    }
}