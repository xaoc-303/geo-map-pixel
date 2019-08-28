<?php
namespace Command;

use PclZip;

class FileUnzipCommand
{
    private $filename;

    public function __construct($filename = 'f.zip')
    {
        $this->filename = $filename;
    }

    public function execute()
    {
        echo date('Y-m-d H:i:s', time()) . PHP_EOL;

        $archive = new PclZip(PATH_STORAGE.DIRECTORY_SEPARATOR.$this->filename);
        if ($archive->extract(PCLZIP_OPT_PATH, "storage") == 0) {
            die("Error : ".$archive->errorInfo(true));
        }

        echo __METHOD__ . ' complete' . PHP_EOL;
    }
}
