<?php
namespace Service;

class GenerateMapStrategyDb extends GenerateMapStrategyAbstract
{
    public function generate(&$map, $width, $height, &$color, $each = 1)
    {
        $this->width = $width;
        $this->height = $height;
        $this->color = $color;

        $db = new Db();
        $db->connect();

        $end = false;
        $offset = 0;
        while (!$end) {
            $rows = $db->getLatLon($offset, $each);
            $offset += $each;

            if (empty($rows)) {
                $end = true;
            } else {
                foreach ($rows as $row) {
                    $this->drawPixelForLatLon($map, $row->latitude, $row->longitude);
                }

                if ($offset % 100000 == 0) {
                    echo $offset.' '.$this->getDate().PHP_EOL;
                }
            }
        }

        $db->connectClose();
    }
}
