# GeoMapPixel

[![Build Status](https://travis-ci.org/xaoc-303/geo-map-pixel.svg?branch=master)](https://travis-ci.org/xaoc-303/geo-map-pixel)

Creates a map of the specified size.
Where are existing cities.

```
composer install
php console.php --download --country=ALL --unzip --generate --each=1 --width=1600 --height=900 --color='255,255,155'
```

```
2019-07-29 00:23:17
Command\FileDownloadCommand::execute complete
2019-07-29 00:25:29
Command\FileUnzipCommand::execute complete
2019-07-29 00:25:47
0 2019-07-29 00:25:47 
1000000 2019-07-29 00:26:00
2000000 2019-07-29 00:26:12
3000000 2019-07-29 00:26:25
4000000 2019-07-29 00:26:39
5000000 2019-07-29 00:26:52
6000000 2019-07-29 00:27:05
7000000 2019-07-29 00:27:18
8000000 2019-07-29 00:27:31
9000000 2019-07-29 00:27:44
10000000 2019-07-29 00:27:57
11000000 2019-07-29 00:28:10
11930946 2019-07-29 00:28:22
Command\GenerateMapCommand::execute complete
```

generated file: `./storage/map1600x900e1.gif`

<img align="center" width="680" src="https://raw.githubusercontent.com/xaoc-303/geo-map-pixel/master/storage/map1600x900e1.gif" />
