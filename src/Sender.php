<?php

namespace Service;

use PhpAmqpLib\Message\AMQPMessage;

/**
 * Class Sender
 * @package Service
 */
class Sender extends RabbitMQService
{
    /**
     * Количество событий, которое будет отправлено в очередь
     */
    const EVENTS_QUANTITY = 10000;

    /**
     * Количество пользователей
     */
    const USERS_QUANTITY = 1000;

    /**
     * Максимальное количество событий отправляемых подряд от одного пользователя
     */
    const MAX_EVENTS_BY_USER = 10;

    /**
     * @var int Счетчик для отправленных сообщений, чтобы прекратить отправку, когда будет достигнуто EVENTS_QUANTITY
     */
    private $count = 0;

    /**
     * @var array Массив с настройками сообщения
     */
    private $msgSettings = ['delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT];

    /**
     * Отправляет сообщения в очередь
     * @throws \Exception
     */
    public function send()
    {
        $userId = 0;
        while (true) {
            $userId = $userId % self::USERS_QUANTITY + 1;
            $n = rand(1, self::MAX_EVENTS_BY_USER);
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
