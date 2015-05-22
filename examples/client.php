<?php

require __DIR__ . '/../vendor/autoload.php';

use Datto\JsonRpc\Ssh\Client;

$destination = getSshDestination();
$command = getRemoteCommand();
$options = getSshOptions();

$client = new Client($destination, $command, $options);

$client->query(1, 'add', array(1, 2));

$reply = $client->send();

print_r($reply); // array('jsonrpc' => '2.0', 'id' => 1, 'result' => 3)


function getSshDestination()
{
    $server = 'localhost';
    $user = posix_getpwuid(posix_geteuid());
    $username = $user['name'];
    return "{$username}@{$server}";
}

function getRemoteCommand()
{
    $scriptPath = realpath(__DIR__ . '/server.php');
    return 'php ' . escapeshellarg($scriptPath);
}

function getSshOptions()
{
    // Example SSH command-line options:
    return array(
        'p' => 22, // use port 22
        'q' => null // enable quiet mode (to suppress most warnings)
    );
}
