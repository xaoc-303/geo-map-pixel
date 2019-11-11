<?php
namespace Service;

class GenerateMap
{
    private $country;
    private $strategyId;
    private $width;
    private $height;
    private $each;
    private $color;
    private $timestampEnabled;

    public function __construct($country, $strategy_id, $width = 1600, $height = 900, $each = 1, $color = '255,255,150', $timestampEnabled = false)
    {
        $this->country = $country;
        $this->strategyId = $strategy_id;
        $this->width = $width;
        $this->height = $height;
        $this->each = $each;
        $this->color = $color;
        $this->timestampEnabled = $timestampEnabled;
    }

    public function run()
    {
        $strategy = GenerateMapStrategy::getStrategy($this->strategyId, $this->country);

        $map = imagecreatetruecolor($this->width, $this->height);
        if ($this->color == 'random') {
            $strategy->setModeRandomColorForCountry(true);
        } else {
            $colors = explode(',', $this->color);
            $color = imagecolorallocate($map, $colors[0], $colors[1], $colors[2]);
        }

        $strategy->generate($map, $this->width, $this->height, $color, $this->each);

        $filename  = 'map';
        $filename .= $this->width . 'x' . $this->height;
        $filename .= 'e' . $this->each;

        if ($this->timestampEnabled) {
            $filename .= '-' . time();
        }

        imagegif($map, PATH_STORAGE . DIRECTORY_SEPARATOR . $filename . '.gif');
        imagedestroy($map);
    }
}
