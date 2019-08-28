<?php
namespace Command;

use Service\GenerateMap;

class GenerateMapCommand
{
    private $country;
    private $each;
    private $width;
    private $height;
    private $color;

    public function __construct($country = 'ALL', $each = 10000, $width = 1600, $height = 900, $color = '255,255,150')
    {
        $this->country = $country;
        $this->each = $each;
        $this->width = $width;
        $this->height = $height;
        $this->color = $color;
    }

    public function execute()
    {
        echo date('Y-m-d H:i:s', time()) . PHP_EOL;

        $fileDownload = new GenerateMap($this->country, 'file', $this->width, $this->height, $this->each, $this->color);
        $fileDownload->run();

        echo __METHOD__ . ' complete' . PHP_EOL;
    }
}
