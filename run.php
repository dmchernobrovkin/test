<?php

`php send.php`;

sleep(1);

$i = 0;
$start = microtime(true);
while (microtime(true) < $start + 1) {
    `nohup php work.php > /dev/null 2>&1 &`;
    $i++;
}
echo $i . "\n";
