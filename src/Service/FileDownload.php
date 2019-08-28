<?php
namespace Service;

class FileDownload
{
    private $url;
    private $filenameDownload = 'f.zip';
    private $strategyId;

    public function __construct($url, $strategy_id)
    {
        $this->url = $url;
        $this->strategyId = $strategy_id;
    }

    public function run()
    {
        $strategy = FileDownloadStrategy::getStrategy($this->strategyId);
        $strategy->download($this->url, PATH_STORAGE.DIRECTORY_SEPARATOR.$this->filenameDownload);
    }
}
