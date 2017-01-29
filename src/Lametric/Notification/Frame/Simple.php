<?php

namespace Jberns\Lametric\Notification\Frame;

class Simple extends Frame {

    protected $iconIdOrBinary;
    protected $text;

    public function __construct($text, $iconIdOrBinary = null) {
        $this->text = $text;
        $this->iconIdOrBinary = $iconIdOrBinary;
    }

    public function jsonSerialize() {
        $data = [
            'text' => $this->text
        ];

        if ($this->iconIdOrBinary) {
            $data['icon'] = $this->iconIdOrBinary;
        }

        return $data;
    }

}
