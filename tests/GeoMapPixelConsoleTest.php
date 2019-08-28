<?php

use PHPUnit\Framework\TestCase;

/**
 * Class GeoMapPixelConsoleTest
 *
 * @example php vendor/bin/phpunit tests/GeoMapPixelConsoleTest.php
 */
class GeoMapPixelConsoleTest extends TestCase
{
    public function testDownload()
    {
        $this->assertFileNotExists(dirname(__FILE__) . "/../storage/map1600x900e11.gif");

        $cmd = "php console.php --download --country=AM --unzip --generate --each=11 --width=1600 --height=900 --color='255,255,155'";
        echo exec($cmd);

        $this->assertFileExists(dirname(__FILE__) . "/../storage/map1600x900e11.gif");
    }
}
