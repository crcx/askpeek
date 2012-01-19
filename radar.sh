#! /bin/bash

cd /home/askpeek/images/weather

wget http://icons-pe.wunderground.com/data/images/us_sf.gif -O /home/askpeek/images/weather/radar.gif
wget http://www.weather.gov/satellite_images/national.jpg -O /home/askpeek/images/weather/IR.jpg
wget http://www.weather.gov/satellite_images/vis_national.jpg -O /home/askpeek/images/weather/satellite.jpg
wget http://www.weather.gov/satellite_images/wv_national.jpg -O /home/askpeek/images/weather/watervapor.jpg
wget http://www.weather.gov/forecasts/graphical/images/conus/MaxT1_conus.png -O /home/askpeek/images/weather/temp.png

convert -resize 630x380 -quality 75 radar.gif radar.jpg
convert -resize 630x380 -quality 75 IR.jpg IR.jpg
convert -resize 630x380 -quality 75 satellite.jpg satellite.jpg

convert -resize 630x380 -quality 75 watervapor.jpg watervapor.jpg
convert -resize 630x380 -quality 75 temp.png temp.jpg

rm radar.gif temp.png
