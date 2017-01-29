<?php

namespace Jberns\Lametric\Handler;

use Jberns\Lametric\Notification;
use Jberns\Lametric\Notification\Frame\Simple;
use Jberns\Lametric\LametricCollection;
use React\Http\Request;
use React\Http\Response;

class NotAllowed implements HandlerInterface {

    public function handle(Request $request, Response $response, array $vars, LametricCollection $lametrics) {
        $response->writeHead(405, array('Content-Type' => 'text/plain'));
        $response->end('Method not allowed');

        $lametrics->notification(new Notification(array(new Simple('Not Allowed'))));
    }

    public function cron(LametricCollection $lametrics) {
        // Nothing to do here
    }

}
