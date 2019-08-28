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

    public function __construct($country, $strategy_id, $width = 1600, $height = 900, $each = 1, $color = '255,255,150')
    {
        $this->country = $country;
        $this->strategyId = $strategy_id;
        $this->width = $width;
        $this->height = $height;
        $this->each = $each;
        $this->color = $color;
    }

    public function run()
    {
        $map = imagecreatetruecolor($this->width, $this->height);
        $colors = explode(',', $this->color);
        $color = imagecolorallocate($map, $colors[0], $colors[1], $colors[2]);

        $strategy = GenerateMapStrategy::getStrategy($this->strategyId, $this->country);
        $strategy->generate($map, $this->width, $this->height, $color, $this->each);

        imagegif($map, PATH_STORAGE.DIRECTORY_SEPARATOR.'map'.$this->width.'x'.$this->height.'e'.$this->each.'.gif');
        imagedestroy($map);
    }
}
