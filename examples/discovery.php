<?php

use Jberns\Lametric\Discoverer;
use Jberns\Lametric\Notification;
use Jberns\Lametric\Notification\Frame\Simple;

require(__DIR__ . '/autoload.php');

// Auto discover Lametrics, The API_KEY must be available for any lemetrics found or they will be ignored
$discoverer = new Discoverer(array(
    'api_keys' => [
        '<DEVICE SERIAL NO.>' => '<DEVICE API KEY>'
    ]
        ));

// Do discovery
$lametrics = $discoverer->discover();

// Error if we don't find any
if ($lametrics->count() === 0) {
    throw new RuntimeException('Failed to find any Lametrics');
} else {
    echo "Found " . $lametrics->count() . " Lametrics.\n";
}

echo "Displaying message. \n";

// Send message to Lametrics
$lametrics->notification(new Notification(array(
    new Simple('Hello world')
)));
