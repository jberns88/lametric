<?php

namespace Jberns\Lametric;

use Clue\React\Ssdp\Client as SsdpClient;
use React\EventLoop\Factory;

class Discoverer {

    /**
     *
     * @var array 
     */
    protected $options;

    /**
     *
     * @var LametricTransceiver 
     */
    protected $transceiver;

    /**
     *
     * @var array 
     */
    protected $defaults = [
        'attempts' => 1,
        'urn' => 'urn:schemas-upnp-org:device:LaMetric:1',
        'duration' => 1,
        'api_keys' => []
    ];

    public function __construct(array $options = [], LametricTransceiver $lametricTransceiver = null) {
        $options = array_merge($this->defaults, $options);
        $this->options = $options;
        $this->transceiver = $lametricTransceiver ?: new LametricTransceiver();
    }

    public function discover(array $options = []) {

        $options = array_merge($this->options, $options);

        $loop = Factory::create();
        $client = new SsdpClient($loop);

        $lametrics = new LametricCollection();

        $i = 0;

        while ($lametrics->count() === 0 && $options['attempts'] > $i) {
            $client->search($options['urn'], $options['duration'])->then(null, null, function ($rawData) use($lametrics) {

                $data = $this->decodeSSDPResourceData($rawData);
               
                $description = $this->transceiver->getSSDPDescription($data['location']);
             
                $key = $this->getApiKey((string) $description->device->serialNumber);

                $lametrics->add(new Lametric((string) $description->URLBase, $key, $this->transceiver));
            });
            $loop->run();
            $i++;
        }

        return $lametrics;
    }

    protected function getApiKey($serialNo) {
        if (isset($this->options['api_keys'][$serialNo])) {
            return $this->options['api_keys'][$serialNo];
        }
        throw new \RuntimeException('Could not find API Key for device with S/N: ' . $serialNo);
    }

    /**
     * 
     * @param string $rawData
     * @return boolean
     */
    protected function decodeSSDPResourceData($rawData) {
        if (!isset($rawData['data'])) {
            return false;
        }

        $data = $rawData['data'];

        $rows = explode("\r\n", $data);
        $rows = array_filter($rows);

        $resourceData = [];

        foreach ($rows as $row) {
            $results = [];
            preg_match('/(^.+):\s(.+)/', $row, $results);

            if (count($results) === 3) {

                $key = $results[1];
                $value = $results[2];
                $resourceData[strtolower($key)] = $value;
            }
        }

        return $resourceData;
    }

}
