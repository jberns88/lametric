<?php

namespace Jberns\Lametric\Handler;

use Jberns\Lametric\Notification;
use Jberns\Lametric\Notification\Frame\Simple;
use Jberns\Lametric\LametricCollection;
use React\Http\Request;
use React\Http\Response;

class NotFound implements HandlerInterface {

    public function handle(Request $request, Response $response, array $vars, LametricCollection $lametrics) {
        $response->writeHead(404, array('Content-Type' => 'text/plain'));
        $response->end('Route not found');

        $lametrics->notification(new Notification(array(new Simple('Not Found'))));
    }

    public function cron(LametricCollection $lametrics) {
        // Nothing to do here
    }

}
