<?php

namespace Jberns\Lametric\Handler;

use Jberns\Lametric\Notification;
use Jberns\Lametric\Notification\Frame\Simple;
use Jberns\Lametric\LametricCollection;
use React\Http\Request;
use React\Http\Response;

/**
 * Demo handler to display "HELLO WORLD"
 */
class HelloWorld implements HandlerInterface {

    public function handle(Request $request, Response $response, array $vars, LametricCollection $lametrics) {
        $response->writeHead(200, array('Content-Type' => 'text/plain'));
        $response->end('Hello world');

        $notification = new Notification(array(
            new Simple('Hello world')
                ), null, 1, Notification::ICON_TYPE_NONE);

        $lametrics->notification($notification);
    }

    public function cron(LametricCollection $lametrics) {
        // Nothing to do here
    }

}
