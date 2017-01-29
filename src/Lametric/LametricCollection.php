<?php

namespace Jberns\Lametric;

use ArrayIterator;
use IteratorAggregate;
use Jberns\Lametric\Notification;

class LametricCollection implements IteratorAggregate {

    /**
     *
     * @var array
     */
    protected $lametrics = [];

    /**
     * 
     * @param array $lametrics
     */
    public function __construct(array $lametrics = []) {
        foreach ($lametrics as $lametric) {
            $this->add($lametric);
        }
    }

    /**
     * 
     * @param Notification $notification
     */
    public function notification(Notification $notification) {
        foreach ($this as $lametric) {
            $lametric->notification($notification);
        }
        return $this;
    }

    /**
     * 
     * @param Lametric $lametric
     * @return $this
     */
    public function add(Lametric $lametric) {
        $this->lametrics[] = $lametric;
        return $this;
    }

    /**
     * 
     * @return int
     */
    public function count(): int {
        return count($this->lametrics);
    }

    /**
     * 
     * @return ArrayIterator
     */
    public function getIterator() {
        return new ArrayIterator($this->lametrics);
    }

}
