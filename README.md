# GeoMapPixel

[![Build Status](https://travis-ci.org/xaoc-303/geo-map-pixel.svg?branch=master)](https://travis-ci.org/xaoc-303/geo-map-pixel)

Creates a dot map of settlements.

```
composer install
php console.php --download --country=ALL --unzip --generate --each=1 --width=3840 --height=2400 --color='255,255,155'
```

generated file: `./storage/map3840x2400e1.gif`

<img style="max-width:100%" src="https://raw.githubusercontent.com/xaoc-303/geo-map-pixel/master/storage/map3840x2400e1.gif" />

```
--color=random
```

<img style="max-width:100%" src="https://raw.githubusercontent.com/xaoc-303/geo-map-pixel/master/storage/map3840x2400e1-color.gif" />

28000x17500 1:5 USA

<img style="max-width:100%" src="https://raw.githubusercontent.com/xaoc-303/geo-map-pixel/master/storage/map28000x17500e1-usa.png" />

28000x17500 1:5 Europe

<img style="max-width:100%" src="https://raw.githubusercontent.com/xaoc-303/geo-map-pixel/master/storage/map28000x17500e1-europe.png" />
