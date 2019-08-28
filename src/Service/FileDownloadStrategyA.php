<?php
namespace Service;

class FileDownloadStrategyA
{
    public function download($url, $filename)
    {
        $content = file_get_contents($url);
        file_put_contents($filename, $content);
    }
}
