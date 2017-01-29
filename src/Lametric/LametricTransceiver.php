<?php

namespace Jberns\Lametric;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Jberns\Lametric\Notification;
use SimpleXMLElement;

class LametricTransceiver {

    /**
     *
     * @var Client 
     */
    protected $client;

    /**
     * 
     */
    public function __construct() {
        $this->client = new Client(['verify' => false]);
    }

    /**
     * 
     * @param string $url
     * @return SimpleXMLElement
     */
    public function getSSDPDescription($url) {
        $client = $this->client;
        $response = $client->get($url);
        $xmlStr = $response->getBody()->getContents();
        $xml = new SimpleXMLElement($xmlStr);
        return $xml;
    }

    /**
     * 
     * @param Lametric $lametric
     * @param Notification $notification
     * @return Response
     */
    public function transmitNotification(Lametric $lametric, Notification $notification) {
        $request = $this->createLametricRequest($lametric, 'POST', '/api/v2/device/notifications');
        $request->getBody()->write(json_encode($notification));
        return $this->client->send($request);
    }

    /**
     * 
     * @param Lametric $lametric
     * @param string $method
     * @param string $url
     * @return Request
     */
    protected function createLametricRequest(Lametric $lametric, string $method, string $url) {
        $address = $lametric->getAddress();
        $address = preg_replace('/^http(s)?\:\/\//', '', $address);

        $address = 'http://' . $address;

        $url = rtrim($address, '/') . '/' . ltrim($url, '/');

        $headers = array(
            'Content-Type' => 'application/json',
            'Authorization' => 'Basic ' . base64_encode('dev:' . $lametric->getAPIKey())
        );

        $request = new Request($method, $url, $headers);

        $uri = $request->getUri();
        $uri = $uri->withScheme('http');
        $uri = $uri->withPort(8080);

        return $request->withUri($uri);
    }

}
