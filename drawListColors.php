<?php

set_time_limit(0);
ini_set('memory_limit', '512M');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once "./src/Service/GenerateMapStrategyAbstract.php";

use Service\GenerateMapStrategyAbstract;

class DrawListColors extends GenerateMapStrategyAbstract
{
    public function draw()
    {
        $filename = 'list_colors.gif';

        $img = imagecreatetruecolor(500, 500);

        for ($i = 0, $step = 10; $i < $this->countColors; $i++, $step += 10) {
            $color = imagecolorallocate($img, $this->listColors[$i][0], $this->listColors[$i][1], $this->listColors[$i][2]);
            imagesetpixel($img, $step, $step, $color);
            imageline($img, $step + 10, $step, $step + 10 + 30, $step, $color);
        }

        imagegif($img, $filename);
    }
}

$class = new DrawListColors();
$class->draw();
