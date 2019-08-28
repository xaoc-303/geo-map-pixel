<?php

use Command\FileDownloadCommand;
use Command\FileUnzipCommand;
use Command\GenerateMapCommand;
use PHPUnit\Framework\TestCase;

define('PATH_STORAGE', __DIR__ .'/../storage');

/**
 * Class GeoMapPixelTest
 *
 * @example php vendor/bin/phpunit tests/GeoMapPixelTest.php
 */
class GeoMapPixelTest extends TestCase
{
    public function test01Download()
    {
        $this->assertFileNotExists(dirname(__FILE__) . "/../storage/f.zip");

        $command = new FileDownloadCommand('AM');
        $command->execute();

        $this->assertFileExists(dirname(__FILE__) . "/../storage/f.zip");
    }

    public function test02Unzip()
    {
        $this->assertFileNotExists(dirname(__FILE__) . "/../storage/AM.txt");

        $command = new FileUnzipCommand();
        $command->execute();

        $this->assertFileExists(dirname(__FILE__) . "/../storage/AM.txt");
    }

    public function test03GenerateMap()
    {
        $this->assertFileNotExists(dirname(__FILE__) . "/../storage/map1600x900e10.gif");

        $command = new GenerateMapCommand('AM', 10, 1600, 900, '255,255,155');
        $command->execute();

        $this->assertFileExists(dirname(__FILE__) . "/../storage/map1600x900e10.gif");
    }
}
