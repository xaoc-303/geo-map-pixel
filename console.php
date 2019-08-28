<?php
set_time_limit(0);
ini_set('memory_limit', '512M');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__.'/vendor/autoload.php';

use Command\FileDownloadCommand;
use Command\FileUnzipCommand;
use Command\GenerateMapCommand;
use Command\InsertIntoDbCommand;

define('PATH_STORAGE', __DIR__ . DIRECTORY_SEPARATOR . 'storage');

$options = getopt("", ['download::','unzip::', 'insert::', 'generate::', 'country:', 'each:', 'width:', 'height:', 'color:']);

$mapCountry = isset($options['country']) ? strtoupper($options['country']) : 'ALL';

if (isset($options['download'])) {
    $command = new FileDownloadCommand($mapCountry);
    $command->execute();
}

if (isset($options['unzip'])) {
    $command = new FileUnzipCommand();
    $command->execute();
}

if (isset($options['insert'])) {
    $command = new InsertIntoDbCommand($mapCountry);
    $command->execute();
}

if (isset($options['generate'])) {
    $mapWidth = isset($options['width']) ? $options['width'] : 1600;
    $mapHeight = isset($options['height']) ? $options['height'] : 900;
    $mapEach = isset($options['each']) ? $options['each'] : 10000;
    $mapColor = isset($options['color']) ? $options['color'] : '255,255,155';

    $command = new GenerateMapCommand($mapCountry, $mapEach, $mapWidth, $mapHeight, $mapColor);
    $command->execute();
}
