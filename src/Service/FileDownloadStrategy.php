<?php
namespace Service;

use Exception;

class FileDownloadStrategy
{
    /**
     * @param $id
     * @return FileDownloadStrategyA|FileDownloadStrategyB
     * @throws Exception
     */
    public static function getStrategy($id)
    {
        switch ($id) {
            case 'A':
                return new FileDownloadStrategyA();
            case 'B':
                return new FileDownloadStrategyB();
            default:
                throw new Exception("Unknown strategy file download");
        }
    }
}
