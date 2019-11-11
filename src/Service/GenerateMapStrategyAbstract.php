<?php
namespace Service;

abstract class GenerateMapStrategyAbstract
{
    protected $width;
    protected $height;
    protected $map;
    protected $color;

    protected $modeRandomColorForCountryEnabled = false;
    protected $currentCountry = '';
    protected $countColors = 10;
    protected $listColors = [
        [0,255,255], // Aqua
        [0,255,0], // Lime
        [128,128,0], // Olive
        [153, 102,204], // Amethyst
        [255,0,0], // Red
        [192,192,192], // Silver
        [0,128,128], // Teal
        [255,255,255], // White
        [255,255,0], // Yellow
        [0, 191, 255], // Deep sky blue
    ];

    protected function getDate()
    {
        return date('H:i:s', time());
    }

    protected function drawPixelForLatLon($lat, $lon)
    {
        extract($this->LatLonToXY($lat, $lon));
        /** @var $x */
        /** @var $y */

        imagesetpixel($this->map, $x, $y, $this->color);
    }

    private function LatLonToXY_($lat, $lon, $zoom = 1)
    {
        //"Converts lat/lon to pixel coordinates in given zoom of the EPSG:4326 pyramid"

        //$res = 180 / 256.0 / $zoom;
        $res = 180 / 256.0 / $zoom;
        //$res = 180 / (float)( 1 << (8+$zoom) );
        $px = (90 + $lat) / $res;
        //$px = -$px + $this->tileSize * 2;
        $py = (180 + $lon) / $res;

        return ['x' => (int) $py, 'y' => (int) $px];
    }

    private function LatLonToXY($lat, $lon)
    {
        $y = ((-1 * $lat) + 90) * ($this->height / 180);
        $x = ($lon + 180) * ($this->width / 360);

        return ['x' => (int) $x, 'y' => (int) $y];
    }

    public function setModeRandomColorForCountry($enable = false)
    {
        $this->modeRandomColorForCountryEnabled = $enable;
    }

    protected function setDrawColorCountry(array &$row)
    {
        if ($this->modeRandomColorForCountryEnabled && $this->currentCountry != $row[8]) {
            $this->currentCountry = $row[8];
            $colorRand = mt_rand(0, $this->countColors-1);
            $this->color = imagecolorallocate($this->map, $this->listColors[$colorRand][0], $this->listColors[$colorRand][1], $this->listColors[$colorRand][2]);
        }
    }
}
