<?php

namespace Jberns\Lametric;

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Jberns\Lametric\Handler\NotAllowed;
use Jberns\Lametric\Handler\NotFound;
use React\EventLoop\Factory;
use React\Http\Request;
use React\Http\Response;
use React\Http\Server as HttpServer;
use React\Socket\Server as SocketServer;
use RuntimeException;
use function FastRoute\simpleDispatcher;

class App {

    /**
     *
     * @var array
     */
    protected $config;

    /**
     *
     * @var LametricCollection 
     */
    protected $lametrics;

    /**
     *
     * @var Dispatcher
     */
    protected $router;

    /**
     * 
     * @param array $config
     * @param LametricCollection $lametrics
     */
    public function __construct(array $config, LametricCollection $lametrics) {
        $this->lametrics = $lametrics;

        $config = array_merge([
            'handlers' => [],
            'not_found_handler' => new NotFound(),
            'not_allowed_handler' => new NotAllowed(),
            'port' => 8080,
            'routes' => [],
                ], $config);


        $this->config = $config;
        $this->createRouter();
    }

    /**
     * 
     */
    protected function createRouter() {
        $defaults = [
            'handler' => false,
            'path' => false,
            'methods' => ['GET']
        ];

        $router = simpleDispatcher(function(RouteCollector $r) use ($defaults) {
            foreach ($this->config['routes'] as $route) {
                $route = array_merge($defaults, $route);

                if (!$route['handler']) {
                    throw new RuntimeException('No handler set for route');
                }
                if (!$route['path']) {
                    throw new RuntimeException('No path set for route');
                }

                $r->addRoute($route['methods'], $route['path'], $route['handler']);
            }
        });

        $this->router = $router;
    }

    /**
     * Start main loop
     */
    public function run() {
        $loop = Factory::create();
        $socket = new SocketServer($loop);
        $http = new HttpServer($socket);

        $http->on('request', function(Request $request, Response $response) {
            $path = $request->getPath();
            $method = $request->getMethod();

            $routeInfo = $this->router->dispatch($method, $path);

            switch ($routeInfo[0]) {
                case Dispatcher::NOT_FOUND:
                    $this->config['not_found_handler']->handle($request, $response, array(), $this->lametrics);
                    break;
                case Dispatcher::METHOD_NOT_ALLOWED:
                    $this->config['not_allowed_handler']->handle($request, $response, array(), $this->lametrics);
                    break;
                case Dispatcher::FOUND:
                    $handler = $this->config['handlers'][$routeInfo[1]];
                    $vars = $routeInfo[2];

                    $handler->handle($request, $response, $vars, $this->lametrics);
                    break;
            }
        });

        $loop->addPeriodicTimer(1, function() {
            foreach ($this->config['handlers'] as $handler) {
                $handler->cron($this->lametrics);
            }
        });

        $socket->listen($this->config['port']);
        $loop->run();
    }

}
