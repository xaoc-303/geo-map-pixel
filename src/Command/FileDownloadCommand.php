<?php
namespace Command;

use Service\FileDownload;

class FileDownloadCommand
{
    private $country;

    public function __construct($country = 'AM')
    {
        $this->country = $country;
    }

    public function execute()
    {
        echo date('Y-m-d H:i:s', time()) . PHP_EOL;

        $url = 'http://download.geonames.org/export/dump/'.('ALL' == $this->country ? 'allCountries' : $this->country).'.zip';

        $fileDownload = new FileDownload($url, 'B');
        $fileDownload->run();

        echo __METHOD__ . ' complete' . PHP_EOL;
    }
}
