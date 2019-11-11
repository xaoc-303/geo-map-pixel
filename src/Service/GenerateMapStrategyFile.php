<?php
namespace Service;

class GenerateMapStrategyFile extends GenerateMapStrategyAbstract
{
    private $filename;

    public function __construct($country)
    {
        $country = strtoupper($country);
        $this->filename = ('ALL' == $country) ? 'allCountries.txt' : $country.'.txt';
    }

    public function generate(&$map, $width, $height, &$color, $each = 1)
    {
        $this->map = $map;
        $this->width = $width;
        $this->height = $height;
        $this->color = $color;

        $file = fopen(PATH_STORAGE.DIRECTORY_SEPARATOR.$this->filename, "r");
        $j = 0;
        $each_i = 1;
        echo $j.' '.$this->getDate().' '.PHP_EOL;

        while (!feof($file)) {
            $row = fgets($file);

            if (++$each_i > $each) {
                if (!empty($row)) {
                    $row_array = explode("\t", $row);

                    $this->setDrawColorCountry($row_array);
                    $this->drawPixelForLatLon($row_array[4], $row_array[5]);
                }
                $each_i = 1;
            }

            if (++$j % 1000000 == 0) {
                echo $j.' '.$this->getDate().PHP_EOL;
            }
        }

        echo $j.' '.$this->getDate().PHP_EOL;
        fclose($file);
    }
}
