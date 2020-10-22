<?php


require_once __DIR__.'/vendor/autoload.php';

use Service\Worker;

(new Worker())->work();