<?php

require __DIR__ . '/../vendor/autoload.php';

use Datto\JsonRpc\Ssh\Example\Server\Translator;
use Datto\JsonRpc\Ssh\Server;

$translator = new Translator();

$server = new Server($translator);

$server->reply();
