<?php

namespace Jberns\Lametric;

use Jberns\Lametric\Notification;

class Lametric {

    /**
     *
     * @var string 
     */
    protected $apiKey;

    /**
     *
     * @var string
     */
    protected $address;

    /**
     *
     * @var LametricTransceiver 
     */
    protected $transceiver;

    /**
     * 
     * @param string $address
     * @param string $apiKey
     * @param LametricTransceiver $lametricTransceiver
     */
    public function __construct(string $address, string $apiKey, LametricTransceiver $lametricTransceiver = null) {
        $this->address = $address;
        $this->apiKey = $apiKey;
        $this->transceiver = $lametricTransceiver ?: new LametricTransceiver();
    }

    /**
     * 
     * @param Notification $notification
     * @return $this
     */
    public function notification(Notification $notification) {
        $this->transceiver->transmitNotification($this, $notification);
        return $this;
    }

    /**
     * 
     * @return string
     */
    public function getAddress(): string {
        return $this->address;
    }

    /**
     * 
     * @return string
     */
    public function getApiKey(): string {
        return $this->apiKey;
    }

}
