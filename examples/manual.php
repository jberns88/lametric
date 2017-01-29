<?php

use Jberns\Lametric\Lametric;
use Jberns\Lametric\LametricCollection;
use Jberns\Lametric\Notification;
use Jberns\Lametric\Notification\Frame\Simple;

require(__DIR__ . '/autoload.php');

//If you know the IP address of the device we can simply enter it's details
$lametrics = new LametricCollection(array(
    new Lametric('<DEVICE ADDRESS>', '<DEVICE API KEY>')
        ));

// Error if we don't find any
if ($lametrics->count() === 0) {
    throw new RuntimeException('Failed to find any Lametrics');
} else {
    echo "Found " . $lametrics->count() . " Lametrics.\n";
}

echo "Displaying message. \n";

$lametrics->notification(new Notification(array(
    new Simple('Hello world')
)));
