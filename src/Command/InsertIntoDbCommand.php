<?php
namespace Command;

use Service\Db;

class InsertIntoDbCommand
{
    private $filename;

    public function __construct($country)
    {
        $country = strtoupper($country);
        $this->filename = ('ALL' == $country) ? 'allCountries.txt' : $country.'.txt';
    }

    public function execute()
    {
        echo date('Y-m-d H:i:s', time()) . PHP_EOL;

        $db = new Db();
        $db->connect();
        $db->createTable();

        $file = fopen(PATH_STORAGE.DIRECTORY_SEPARATOR.$this->filename, "r");
        $line_i = 0;
        $current_country = null;

        while (!feof($file)) {
            $row = fgets($file);
            if (!empty($row)) {
                $row_array = explode("\t", $row);
                $db->insertRow($row_array);

                if ($current_country != $row_array[8]) {
                    $current_country = $row_array[8];
                    echo ++$line_i.' '.$current_country.' '.$row_array[0].' '.$row_array[2].PHP_EOL;
                }
            }
        }

        fclose($file);
        $db->connectClose();

        echo __METHOD__.' complete'.PHP_EOL;
    }
}
