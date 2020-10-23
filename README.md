# test

Сервис требует установки RabbitMQ. Если он не установлен, то для Ubuntu / Debian устанавливается и запускается так:

```bash
echo 'deb http://www.rabbitmq.com/debian/ testing main' | sudo tee /etc/apt/sources.list.d/rabbitmq.list
wget -O- https://www.rabbitmq.com/rabbitmq-release-signing-key.asc | sudo apt-key add -

sudo apt-get update
sudo apt-get install rabbitmq-server

sudo systemctl start rabbitmq-server
```

Для установки требуемых php-пакетов нужно воспользоваться composer.

Если composer установлен глобально, можно запустить

```bash
composer install
```

Если composer не установлен глобально, то можно запустить скрипт, который сразу установит composer и требуемые пакеты:

```bash
php install.php
```

Для запуска сервиса нужно запустить

```bash
php run.php
```

Этот скрипт вначале сгенерирует события, а затем в течение 1 секунды последовательно запустит обработчики. Результат сохранится в файле events.log.

Для удаления очереди и завершения всех обработчиков можно использовать комманду

```bash
sudo rabbitmqctl delete_queue event_queue
```