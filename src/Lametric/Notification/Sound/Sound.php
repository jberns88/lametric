<?php

namespace Jberns\Lametric\Notification\Sound;

abstract class Sound implements \JsonSerializable {

    protected $repeat = 1;
    protected $sound;
    protected $category;

    public function __construct($sound, int $repeat = 1) {
        $this->sound = $sound;
        $this->repeat = $repeat;
    }

    public function jsonSerialize() {
        return [
            'category' => $this->category,
            'id' => $this->sound,
            'repeat' => $this->repeat
        ];
    }

}
