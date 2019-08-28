<?php
namespace Service;

class FileDownloadStrategyB
{
    public function download($url, $filename)
    {
        $fRead = fopen($url, 'r');
        $fWrite = fopen($filename, 'w');
        if ($fRead) {
            while (!feof($fRead)) {
                fwrite($fWrite, fgets($fRead, 4096));
            }
            fclose($fRead);
        }
    }
}
