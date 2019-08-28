<?php
namespace Service;

abstract class GenerateMapStrategyAbstract
{
    protected $width;
    protected $height;
    protected $map;
    protected $color;

    protected function getDate()
    {
        return date('Y-m-d H:i:s', time());
    }

    protected function drawPixelForLatLon(&$map, $lat, $lon)
    {
        extract($this->LatLonToXY($lat, $lon));
        /** @var $x */
        /** @var $y */

        imagesetpixel($map, $x, $y, $this->color);
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
}
