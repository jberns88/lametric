<?php

use Jberns\Lametric\App;
use Jberns\Lametric\Discoverer;
use Jberns\Lametric\LametricCollection;
use Jberns\Lametric\Notification;
use Jberns\Lametric\Notification\Frame\Simple;
use React\Http\Request;
use React\Http\Response;

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

//Create a new Lametic app it includes a HTTP server (reactPHP)
$app = new App(array(
    // Port the HTTP server will run on
    'port' => 8080,
    //Defined routes, the handler is the key of the handers array
    'routes' => [
            [
            'path' => '/{text}',
            'methods' => ['GET'],
            'handler' => 'text'
        ]
    ],
    'handlers' => [
        //Handler must have the methods handle() and cron()
        'text' => new class {

            //Count down timer
            protected $time = 0;

            //Simple handler to display any text send to the server in the URL
            function handle(Request $request, Response $response, array $vars, LametricCollection $lametrics) {
                //Decode the text
                $text = urldecode((string) $vars['text']);

                //Send notification
                $lametrics->notification(new Notification(array(
                    new Simple($text)
                )));

                //Reply to HTTP request
                $response->writeHead(200, ['Content-Type' => 'text/plain']);
                $response->end('OK');
            }

            // Cron task that will run every second (do not rely on the accuracy 
            // of it to run every second as other handlers may slow it down)
            function cron(LametricCollection $lametrics) {
                //Send notification every 20 seconds
                if ($this->time <= time()) {
                    $lametrics->notification(new Notification(array(
                        new Simple('Scheduled message')
                    )));
                    $this->time = time() + 20;
                }
            }
        }],
        ), $lametrics);


$app->run();
