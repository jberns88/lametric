<?php

namespace Jberns\Lametric;

use JsonSerializable;
use Jberns\Lametric\Notification\Frame\Frame;
use Jberns\Lametric\Notification\Sound\Sound;

class Notification implements JsonSerializable {

    const ICON_TYPE_NONE = 'none';
    const ICON_TYPE_INFO = 'info';
    const ICON_TYPE_ALERT = 'alert';

    protected $cycles = 1;
    protected $frames = [];
    protected $sound;
    protected $iconType;

    /**
     * 
     * @param array $frames
     * @param Sound $sound
     * @param int $cycles
     * @param string $iconType
     */
    public function __construct(array $frames = [], Sound $sound = null, int $cycles = 1, $iconType = self::ICON_TYPE_INFO) {
        $this->addFrames($frames);
        if ($sound) {
            $this->setSound($sound);
        }
        $this->setCycles($cycles);
        $this->setIconType($iconType);
    }

    /**
     * 
     * @param string $iconType
     * @return $this
     */
    public function setIconType($iconType = self::ICON_TYPE_INFO) {
        $this->iconType = $iconType;
        return $this;
    }

    /**
     * Number of times the notification should be displayed
     * @param int $cycles
     */
    public function setCycles(int $cycles) {
        $this->cycles = $cycles;
        return $this;
    }

    /**
     * 
     * @return int
     */
    public function getCycles(): int {
        return $this->cycles;
    }

    /**
     * 
     * @param Sound $sound
     * @return $this
     */
    public function setSound(Sound $sound) {
        $this->sound = $sound;
        return $this;
    }

    /**
     * 
     * @param array $frames
     * @return $this
     */
    public function addFrames(array $frames) {
        foreach ($frames as $frame) {
            $this->addFrame($frame);
        }
        return $this;
    }

    /**
     * 
     * @param Frame $frame
     */
    public function addFrame(Frame $frame) {
        $this->frames[] = $frame;
        return $this;
    }

    /**
     * 
     * @return array
     */
    public function jsonSerialize(): array {
        $data = [
            'model' => [
                'cycles' => $this->cycles,
                'frames' => $this->frames,
            ],
            'icon_type' => $this->iconType
        ];
        if ($this->sound) {
            $data['model']['sound'] = $this->sound;
        }

        return $data;
    }

}
