<?php

set_time_limit(0);
ini_set('memory_limit', '512M');

class Geo {

	public function __construct($data = array()) {
		echo $this->get_date().PHP_EOL;

		if (empty($data)) {
			die('not parameter');
		}

		switch ($data[1]) {
			case 'download':
				$this->download_1('http://download.geonames.org/export/dump/allCountries.zip');
			break;
			case 'unzip':
				$this->unzip();
			break;
			case 'to_db':
				$this->to_db('allCountries.txt');
			break;
			case 'create_map':
				$from = (isset($data[2])) ? ( $data[2]=='file' ? 'file' : 'db' ) : 'file';	// file | db
				$width = (isset($data[3])) ? (int) $data[3] : 1600;							// map width
				$height = (isset($data[4])) ? (int) $data[4] : 900;							// map height
				$each = (isset($data[5])) ? (int) $data[5] : 1;								// step
				if ($from == 'file') {
					$this->create_map(true, 'allCountries.txt', $width, $height, $each);
				} else {
					$this->create_map(false, null, $width, $height, $each);
				}
				
			break;
			case 'all':
				$this->__construct([null,'download']);
				$this->__construct([null,'unzip']);
				$this->__construct([null,'create_map', 'file', 1600, 900, 1]);
			break;
			default:
				echo 'select parameter: download | unzip | to_db | create_map_db | create_map_file | all'.PHP_EOL;
			break;
		}
	}

	private $mysqli = null;
	
	private function download($url = 'http://download.geonames.org/export/dump/RU.zip') {
		$content = file_get_contents($url);
		file_put_contents ( 'f.zip', $content );
		echo __FUNCTION__.' complete'.PHP_EOL;

	}

	private function download_1($url = 'http://download.geonames.org/export/dump/RU.zip') {
		$handle = fopen($url,'r');
		$handle2 = fopen(__DIR__.'/f.zip','w');
		if ($handle) {
			while (!feof($handle)) {
				fwrite($handle2, fgets($handle, 4096));
			}
			fclose($handle);
		}
		echo __FUNCTION__.' complete'.PHP_EOL;
	}

	private function unzip($file = 'f.zip') {
		require_once('pclzip.lib.php');
		$archive = new PclZip($file);
		if ($archive->extract() == 0) {
			die("Error : ".$archive->errorInfo(true));
		}
		echo __FUNCTION__.' complete'.PHP_EOL;
	}

	private function get_parsing_files($template = '*.txt') {
		$files = scandir(__DIR__);

		$need_files = [];

		foreach ($files as $file) {
			if (fnmatch($template,$file)) {
				$need_files[] = $file;
			}
		}

		return $need_files;
	}

	private function create_table() {
		return "CREATE TABLE IF NOT EXISTS `geonames_org` (
	  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	  `geonameid` int(10) unsigned NOT NULL,
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
	}

	private function connect_to_db() {
		$this->mysqli = new mysqli("localhost", "root", "", "test");

		// check connection
		if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_error());
			exit;
		}
		$this->mysqli->set_charset("utf8");

		printf("Host information: %s\n", $this->mysqli->host_info);
	}

	private function connect_close() {

		$this->mysqli->close();

		echo __FUNCTION__.' '.$this->get_date().PHP_EOL;

	}

	private function convert_for_fetch($rows) {

		$return = array();

		while( $row = $rows->fetch_object() ){
			array_push($return, $row);
		}
		$rows = null;

		return $return;
	}

	private function to_db($template = '*.txt') {
		
		$files = $this->get_parsing_files($template);
		$files_count = count($files);

		$this->connect_to_db();
		$result = $this->mysqli->query($this->create_table());

		//$result = $this->mysqli->query("TRUNCATE TABLE `geonames_org`");

		for ($i=0; $i < $files_count; $i++) {

			$file = fopen(__DIR__.'/'.$files[$i], "r");
			$row_i = 0;
			$current_country = null;
			while (!feof($file)) {
				$row_i++;
				
				$row = fgets($file);
				if (!empty($row) ) {
					$row_array = explode("\t", $row);
					$row_array[1] = $this->mysqli->real_escape_string($row_array[1]);
					$row_array[2] = $this->mysqli->real_escape_string($row_array[2]);
					$row_array[3] = $this->mysqli->real_escape_string($row_array[3]);

					$query = "INSERT INTO `geonames_org`
					(`geonameid`, `name`, `asciiname`, `alternatenames`, `latitude`, `longitude`, `feature_class`, `feature_code`, `country_code`, `cc2`, `admin1_code`, `admin2_code`, `admin3_code`, `admin4_code`, `population`, `elevation`, `dem`, `timezone`, `modification_date`)
					VALUES
					('$row_array[0]', '$row_array[1]', '$row_array[2]', '$row_array[3]', '$row_array[4]', '$row_array[5]', '$row_array[6]', '$row_array[7]', '$row_array[8]', '$row_array[9]', '$row_array[10]', '$row_array[11]', '$row_array[12]', '$row_array[13]', '$row_array[14]', '$row_array[15]', '$row_array[16]', '$row_array[17]', '$row_array[18]')";
					
					$result = $this->mysqli->query($query);

					if ($current_country != $row_array[8]) {
						$current_country = $row_array[8];
						echo $files[$i].' '.$row_i.' '.$current_country.' '.$row_array[0].' '.$row_array[2].PHP_EOL;
					}
				}
			}
			fclose($file);
		}
		$this->connect_close();

		echo __FUNCTION__.' complete'.PHP_EOL;
	}

	private function get_date(){
		return date('Y-m-d H:i:s', time());
	}

	private function LatLonToPixels_($lat, $lon, $zoom = 1){
        //"Converts lat/lon to pixel coordinates in given zoom of the EPSG:4326 pyramid"

        //$res = 180 / 256.0 / $zoom;
        $res = 180 / 256.0 / $zoom;
        //$res = 180 / (float)( 1 << (8+$zoom) );
        $px = (90 + $lat) / $res;
        //$px = -$px + $this->tileSize * 2;
        $py = (180 + $lon) / $res;

        return array( 'px' => (int) $py, 'py' => (int) $px);
    }

    private function LatLonToPixels($lat, $lon,$width,$height){
    	$y = ((-1 * $lat) + 90) * ($height / 180);
    	$x = ($lon + 180) * ($width / 360);
    	return ['x' => (int) $x, 'y' => (int) $y];
	}

	private function create_map($is_file = true, $template = '*.txt', $width = 1600, $height = 900, $each = 1) {

		$map = imagecreatetruecolor($width, $height);
		$color = imagecolorallocate($map, 255, 255, 150);

		if ($is_file) {
			$this->map_from_file($map, $width, $height, $color, $each, $template);
		} else {
			$this->map_from_db($map, $width, $height, $color, $each);
		}

		// text
		//$size = 12;
		//$font = 'fonts/totshrift-normal.ttf';
		//$text = $width.'x'.$height.' zoom='.$zoom;
		//$color = imagecolorallocate($map, 255, 255, 255);
		//imagettftext($map, $size, 0, 50, 50, $color, $font, $text);

		//header("Content-type: image/gif");
		imagegif($map,'map'.$width.'x'.$height.'e'.$each.'.gif');
		imagedestroy($map);

		echo __FUNCTION__.' complete'.PHP_EOL;
	}

	private function map_from_file(&$map, $width, $height, &$color, $each = 1, $template = '*.txt') {

		$width = $width / 360;
		$height = $height / 180;

		$files = $this->get_parsing_files($template);
		$files_count = count($files);

		for ($i=0; $i < $files_count; $i++) {

			$file = fopen(__DIR__.'/'.$files[$i], "r");
			$j = 0;
			$each_i = 1;
			echo $j.' '.$this->get_date().' '.PHP_EOL;
			
			while (!feof($file)) {
				$row = fgets($file);

				if(++$each_i > $each) {
					if (!empty($row) ) {
						$row_array = explode("\t", $row);

						$lat = $row_array[4];
						$lon = $row_array[5];

						$y = ((-1 * $lat) + 90) * $height;
						$x = ($lon + 180) * $width;
						//extract ($this->LatLonToPixels($lat, $lon, $width, $height));
						imagesetpixel($map, $x, $y, $color);
					}
					$each_i = 1;
				}

				if (++$j % 1000000 == 0) {
					echo $j.' '.$this->get_date().''.PHP_EOL;
				}
				
			}
			echo $j.' '.$this->get_date().''.PHP_EOL;
			fclose($file);
		}
	}

	private function map_from_db(&$map, $width, $height, &$color, $each = 1) {

		$width = $width / 360;
		$height = $height / 180;

		$this->connect_to_db();

		$end = false;
		$offset = 0;
		while(!$end) {
			$query = 'SELECT `latitude`, `longitude` FROM `geonames_org` LIMIT '.$offset.', 100000';
			$rows = $this->mysqli->query($query);
			$offset += 100000;
			$rows = $this->convert_for_fetch($rows);

			if(empty($rows)) {
				$end = true;
			} else{

				foreach($rows as $row){
					//extract ($this->LatLonToPixels($row->latitude, $row->longitude, $width, $height));
					$y = ((-1 * $lat) + 90) * $height;
					$x = ($lon + 180) * $width;
					imagesetpixel($map, $x, $y, $color);
				}

				if ($offset % 100000 == 0) {
					//break;
					echo $offset.' '.$this->get_date().PHP_EOL;
				}

			}
		}

		$this->connect_close();
		return $map;
	}

}

if(isset($argv)){
	$class = new Geo($argv);
}

exit;