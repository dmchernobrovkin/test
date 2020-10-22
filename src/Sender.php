<?php

namespace Service;

use PhpAmqpLib\Message\AMQPMessage;

class Sender extends Service
{
    const EVENTS_QUANTITY = 10000;
    const USERS_QUANTITY = 1000;
    const MAX_EVENTS_BY_USER = 10;

    private $count = 0;

    private $msgSettings = ['delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT];

    public function send()
    {
        while (true) {
            $n = rand(1, self::MAX_EVENTS_BY_USER);
            $userId = rand(1, self::USERS_QUANTITY);
            for($i = 0; $i < $n; $i++) {
                $this->count++;
                if ($this->count > self::EVENTS_QUANTITY) {
                    break 2;
                }
                $data = json_encode([
                    'event_id' => $this->count,
                    'user_id' => $userId
                ]);
                $msg = new AMQPMessage($data, $this->msgSettings);
                $this->channel->basic_publish($msg, '', self::QUEUE_NAME);
            }
        }
        $this->channel->close();
        $this->connection->close();
    }
}
