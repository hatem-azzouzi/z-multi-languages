<?php

namespace zmultilang\conf;

$host = getenv('host') ?: $_SERVER['HTTP_HOST'];

if (!$host) {
    throw new ZmultilangConfigException('Host missing');
}

return array(
    __DIR__ . '/config.php',
    __DIR__ . "/config.$host.php",
    __DIR__ . "/config." . strtolower(gethostname()) . '.php'
);

class ZmultilangConfigException extends \Exception {}