<?php
namespace Service;

use mysqli;

class Db
{
    /**
     * @var mysqli
     */
    private $mysqli;

    public function connect()
    {
        $this->mysqli = new mysqli("localhost", "root", "root");

        $this->mysqli->query("CREATE DATABASE IF NOT EXISTS `geonames`");
        $this->mysqli->select_db('geonames');

        // check connection
        if (mysqli_connect_errno()) {
            printf("Connect failed: %s\n", mysqli_connect_error());
            exit;
        }
        $this->mysqli->set_charset("utf8");

        printf("Host information: %s\n", $this->mysqli->host_info);

        return $this->mysqli;
    }

    public function connectClose()
    {
        $this->mysqli->close();
    }

    public function createTable()
    {
        $sql = "CREATE TABLE IF NOT EXISTS `geonames` (
          `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
          `geoname_id` int(10) unsigned NOT NULL,
          `name` varchar(200) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
          `asciiname` varchar(200) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
          `alternatenames` varchar(5000) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
          `latitude` decimal(10,5) NOT NULL,
          `longitude` decimal(10,5) NOT NULL,
          `feature_class` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
          `feature_code` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
          `country_code` varchar(2) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
          `cc2` varchar(60) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
          `admin1_code` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
          `admin2_code` varchar(80) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
          `admin3_code` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
          `admin4_code` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
          `population` bigint(20) NOT NULL,
          `elevation` int(11) NOT NULL,
          `dem` int(11) NOT NULL,
          `timezone` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
          `modification_date` date NOT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1";

        $this->mysqli->query($sql);
    }

    public function insertRow($row)
    {
        $count = count($row);
        for ($i = 0; $i < $count; $i++) {
            $row[$i] = $this->mysqli->real_escape_string($row[$i]);
        }

        $sql = "INSERT INTO `geonames`
            (`geoname_id`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature_class`, `feature_code`, `country_code`, `cc2`, `admin1_code`, `admin2_code`, `admin3_code`, `admin4_code`, `population`, `elevation`, `dem`, `timezone`, `modification_date`)
            VALUES
            ('$row[0]', '$row[1]', '$row[2]', '$row[3]', '$row[4]', '$row[5]', '$row[6]', '$row[7]', '$row[8]', '$row[9]', '$row[10]', '$row[11]', '$row[12]', '$row[13]', '$row[14]', '$row[15]', '$row[16]', '$row[17]', '$row[18]')";

        $result = $this->mysqli->query($sql);
    }

    public function getLatLon($offset, $limit)
    {
        $query = "SELECT `latitude`, `longitude` FROM `geonames` LIMIT $offset, $limit";
        $rows = $this->mysqli->query($query);

        $return = [];

        while ($row = $rows->fetch_object()) {
            array_push($return, $row);
        }

        return $return;
    }
}
