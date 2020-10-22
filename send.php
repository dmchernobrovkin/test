<?php

require_once __DIR__.'/vendor/autoload.php';

use Service\Sender;

(new Sender())->send();

echo "OK\n";