<?php

namespace Jberns\Lametric\Handler;

use Jberns\Lametric\LametricCollection;
use React\Http\Request;
use React\Http\Response;

interface HandlerInterface {

    /**
     * Handle the request
     * @param Request $request
     * @param Response $response
     * @param array $vars
     * @param LametricCollection $lametrics
     */
    public function handle(Request $request, Response $response, array $vars, LametricCollection $lametrics);

    /**
     * Periodic cron task to be executed once a second
     * @param LametricCollection $lametrics
     */
    public function cron(LametricCollection $lametrics);
}
