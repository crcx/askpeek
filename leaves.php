<?php

function ask_local()
{
  global $message, $who, $type, $subject, $body;
  $subject = str_replace(' ', '+', $message);
  $html = get_url_contents("http://maps.google.com/maps?hl=en&ie=UTF8&z=14&view=text&ei=je_ESebqMqa2oAPqns2iDA&attrid=&output=html&pw=2&q=".$subject);
  $body = minimalize($html);
  $body = $body . "\n-----\nThis is a free service provided by Charles Childers. If you find it useful,";
  $body = $body . " please consider making a small donation to help offset costs at https://www.wepay.com/donate/49365";
  $header = "From: Local Search <local@retroforth.org>\r\n";
  mail($who, "Search Results for: " . $src, $body, $header);
}


function strbet($inputStr, $delimeterLeft, $delimeterRight, $debug=false) { 
    $posLeft=strpos($inputStr, $delimeterLeft); 
    if ( $posLeft===false ) { 
        if ( $debug ) { 
            echo "Warning: left delimiter '{$delimeterLeft}' not found"; 
        } 
        return false; 
    } 
    $posLeft+=strlen($delimeterLeft); 
    $posRight=strpos($inputStr, $delimeterRight, $posLeft); 
    if ( $posRight===false ) { 
        if ( $debug ) { 
            echo "Warning: right delimiter '{$delimeterRight}' not found"; 
        } 
        return false; 
    } 
    return substr($inputStr, $posLeft, $posRight-$posLeft); 
} 

function ask_maps()
{
  global $message, $who, $type, $subject, $body;
  $subject = $message;
  $key = "your-api-key";
  $url = str_replace(" ", "+", $subject);
  $type = "map";
  $body = "Map Results for '".$subject."' are attached.\n";

  $src = get_url_contents("http://maps.google.com/maps/geo?output=xml&oe=utf8&sensor=false&key=".$key."&q=".$url);
  echo $src;
/*
  $src = str_replace('<Locality>', '', $src);
  $src = str_replace('</Locality>', '', $src);
  $src = str_replace('<SubAdministrativeArea>', '', $src);
  $src = str_replace('</SubAdministrativeArea>', '', $src);

  $xml  = new SimpleXMLElement(utf8_decode($src));
  $coordinates = $xml->Response->Placemark->Point->coordinates;
*/

  $coordinates = strbet($src, "<coordinates>", "</coordinates>"); 
  $coordinatesSplit = split(",", $coordinates);

  // Format: Longitude, Latitude, Altitude
  $lat = $coordinatesSplit[1];
  $lng = $coordinatesSplit[0];
  $geo=$lat.",".$lng;

  $body = $body . "Coordinates for address: ".$geo;
  $body = $body . "\n-----\nThis is a free service provided by Charles Childers. If you find it useful,";
  $body = $body . " please consider making a small donation to help offset costs at https://www.wepay.com/donate/49365";

  download("/home/askpeek/images/map/map.gif", 'http://maps.google.com/staticmap?key='.$key.'&maptype=mobile&format=gif&size=630x380&zoom=17&sensor=false&center='.$geo.'&markers='.$geo.',blue');
  download("/home/askpeek/images/map/overview.gif", 'http://maps.google.com/staticmap?key='.$key.'&maptype=mobile&format=gif&size=630x380&zoom=12&sensor=false&center='.$geo.'&markers='.$geo.',blue');
  sendMessage($who, "Map Results", $body, array("/home/askpeek/images/map/map.gif", "/home/askpeek/images/map/overview.gif"), "Maps <maps@retroforth.org>");
}



function ask_news()
{
  global $message, $who, $type, $subject, $body;

  $message = strtolower($message);

  /* NPR */
  $feed[1] = 'http://www.npr.org/rss/rss.php?id=1001';     /* Top Stories */
  $feed[2] = 'http://www.npr.org/rss/rss.php?id=1003';     /* US News */
  $feed[3] = 'http://www.npr.org/rss/rss.php?id=1004';     /* World News */

  /* BBC Top Stories */
  $feed[4] = 'http://newsrss.bbc.co.uk/rss/newsonline_world_edition/front_page/rss.xml';

  /* NY Times */
  $feed[5] = 'http://www.nytimes.com/services/xml/rss/nyt/HomePage.xml';

  $source = $feed[1];

  if ($message == "us")
    $source = $feed[2];

  if ($message == "world")
    $source = $feed[3];

  if ($message == "bbc")
    $source = $feed[4];

  if ($message == "nytimes" || $message == "ny times")
    $source = $feed[5];

  $rss = new lastRSS;
  $rss->cache_dir = '/tmp';
  $rss->cache_time = 60;
  $body = "";
  $type = "rss-news";
  if ($rs = $rss->get($source))
  {
    $body = "Feed for $rs[title]\n\n";
    foreach($rs['items'] as $item)
    {
      $body .= "== " . $item['title'] . " ==\n";
      $body .= $item['description'] . "\n\n";
    }
  }
  else
  {
    $body = "Error: Unable to load RSS feed!\n";
  }

  $b = $body;
  $c = str_replace("&lt;strong&gt;", "", $b);
  $b = str_replace("&lt;em&gt;", "", $c);
  $c = str_replace("&lt;/strong&gt;", "", $b);
  $b = str_replace("&lt;/em&gt;", "", $c);
  $c = str_replace("&lt;", "<", $b);
  $b = str_replace("&gt;", ">", $c);
  $c = str_replace("<hr>", "\n", $b);
  $b = str_replace("<hr/>", "\n", $c);
  $c = str_replace("<hr />", "\n", $b);
  $b = str_replace("<br />", "\n", $c);
  $c = str_replace("<br>", "\n", $b);
  $b = str_replace("<br/>", "\n", $c);
  $c = utf8_html_entity_decode($b);
  $b = strip_tags($c);

  $body = $b;
  $body = $body . "\n-----\nThis is a free service provided by Charles Childers. If you find it useful,";
  $body = $body . " please consider making a small donation to help offset costs at https://www.wepay.com/donate/49365";

  $header = "From: News Headlines <news@retroforth.org>\r\n";
  mail($who, "Recent Headlines", $body, $header);
}



function ask_traffic()
{
  global $message, $who, $type, $subject, $body;

  /* La East */
    $a = preg_match('/la east(.*)$/i', $message, $matches);
    $subject = $matches[1];
    if ($a == 1)
    {
      $type = "traffic";
      download("/home/askpeek/images/traffic/base.png", "http://sigalertmaps.s3.amazonaws.com/LAEast.png");
      download("/home/askpeek/images/traffic/overlay.gif", "http://old.sigalert.com/MapGraphicForeground.asp?Region=LA+East");

      system("convert /home/askpeek/images/traffic/base.png /home/askpeek/images/traffic/base.gif");
      system("convert -flatten -gravity center /home/askpeek/images/traffic/base.gif /home/askpeek/images/traffic/overlay.gif /home/askpeek/images/traffic/traffic.gif");
      system("convert -resize 640x -format jpg-baseline -quality 75 /home/askpeek/images/traffic/traffic.gif /home/askpeek/images/traffic/traffic.jpg; rm /home/askpeek/images/traffic/traffic.gif");
      system("convert -crop 640x448+0+0 +repage /home/askpeek/images/traffic/traffic.jpg /home/askpeek/images/traffic/t1.jpg");
      system("convert -crop 640x448+0+448 +repage /home/askpeek/images/traffic/traffic.jpg /home/askpeek/images/traffic/t2.jpg");
      sendMessage($who, "Traffic Results", "Check the attachment for a map", array("/home/askpeek/images/traffic/t1.jpg", "/home/askpeek/images/traffic/t2.jpg"), "Traffic <traffic@retroforth.org>");
    }


    /* LA West */
    $a = preg_match('/la west(.*)$/i', $message, $matches);
    $subject = $matches[1];
    if ($a == 1)
    {
      $type = "traffic";
      download("/home/askpeek/images/traffic/base.png", "http://sigalertmaps.s3.amazonaws.com/LAWest.png");
      download("/home/askpeek/images/traffic/overlay.gif", "http://old.sigalert.com/MapGraphicForeground.asp?Region=LA+West");

      system("convert /home/askpeek/images/traffic/base.png /home/askpeek/images/traffic/base.gif");
      system("convert -flatten -gravity center /home/askpeek/images/traffic/base.gif /home/askpeek/images/traffic/overlay.gif /home/askpeek/images/traffic/traffic.gif");
      system("convert -resize 640x -format jpg-baseline -quality 75 /home/askpeek/images/traffic/traffic.gif /home/askpeek/images/traffic/traffic.jpg; rm /home/askpeek/images/traffic/traffic.gif");
      system("convert -crop 640x448+0+0 +repage /home/askpeek/images/traffic/traffic.jpg /home/askpeek/images/traffic/t1.jpg");
      system("convert -crop 640x448+0+448 +repage /home/askpeek/images/traffic/traffic.jpg /home/askpeek/images/traffic/t2.jpg");
      sendMessage($who, "Traffic Results", "Check the attachment for a map", array("/home/askpeek/images/traffic/t1.jpg", "/home/askpeek/images/traffic/t2.jpg"), "Traffic <traffic@retroforth.org>");
    }


    /* San Fransisco */
    $a = preg_match('/sf(.*)$/i', $message, $matches);
    $subject = $matches[1];
    if ($a == 1)
    {
      $type = "traffic";
      download("/home/askpeek/images/traffic/base.png", "http://sigalertmaps.s3.amazonaws.com/BayArea.png");
      download("/home/askpeek/images/traffic/overlay.gif", "http://old.sigalert.com/MapGraphicForeground.asp?Region=Bay+Area");

      system("convert /home/askpeek/images/traffic/base.png /home/askpeek/images/traffic/base.gif");
      system("convert -flatten -gravity center /home/askpeek/images/traffic/base.gif /home/askpeek/images/traffic/overlay.gif /home/askpeek/images/traffic/traffic.gif");
      system("convert -resize 640x -format jpg-baseline -quality 75 /home/askpeek/images/traffic/traffic.gif /home/askpeek/images/traffic/traffic.jpg; rm /home/askpeek/images/traffic/traffic.gif");
      system("convert -crop 640x448+0+0 +repage /home/askpeek/images/traffic/traffic.jpg /home/askpeek/images/traffic/t1.jpg");
      system("convert -crop 640x448+0+448 +repage /home/askpeek/images/traffic/traffic.jpg /home/askpeek/images/traffic/t2.jpg");
      sendMessage($who, "Traffic Results", "Check the attachment for a map", array("/home/askpeek/images/traffic/t1.jpg", "/home/askpeek/images/traffic/t2.jpg"), "Traffic <traffic@retroforth.org>");
    }


    /* San Diego */
    $a = preg_match('/sd(.*)$/i', $message, $matches);
    $subject = $matches[1];
    if ($a == 1)
    {
      $type = "traffic";
      download("/home/askpeek/images/traffic/base.png", "http://sigalertmaps.s3.amazonaws.com/SanDiego.png");
      download("/home/askpeek/images/traffic/overlay.gif", "http://old.sigalert.com/MapGraphicForeground.asp?Region=San+Diego");

      system("convert /home/askpeek/images/traffic/base.png /home/askpeek/images/traffic/base.gif");
      system("convert -flatten -gravity center /home/askpeek/images/traffic/base.gif /home/askpeek/images/traffic/overlay.gif /home/askpeek/images/traffic/traffic.gif");
      system("convert -resize 640x -format jpg-baseline -quality 75 /home/askpeek/images/traffic/traffic.gif /home/askpeek/images/traffic/traffic.jpg; rm /home/askpeek/images/traffic/traffic.gif");
      system("convert -crop 640x448+0+0 +repage /home/askpeek/images/traffic/traffic.jpg /home/askpeek/images/traffic/t1.jpg");
      system("convert -crop 640x448+0+448 +repage /home/askpeek/images/traffic/traffic.jpg /home/askpeek/images/traffic/t2.jpg");
      sendMessage($who, "Traffic Results", "Check the attachment for a map", array("/home/askpeek/images/traffic/t1.jpg", "/home/askpeek/images/traffic/t2.jpg"), "Traffic <traffic@retroforth.org>");
    }


    /* Inland Empire */
    $a = preg_match('/inland empire(.*)$/i', $message, $matches);
    $subject = $matches[1];
    if ($a == 1)
    {
      $type = "traffic";
      download("/home/askpeek/images/traffic/base.png", "http://sigalertmaps.s3.amazonaws.com/InlandEmpire.png");
      download("/home/askpeek/images/traffic/overlay.gif", "http://old.sigalert.com/MapGraphicForeground.asp?Region=Inland+Empire");

      system("convert /home/askpeek/images/traffic/base.png /home/askpeek/images/traffic/base.gif");
      system("convert -flatten -gravity center /home/askpeek/images/traffic/base.gif /home/askpeek/images/traffic/overlay.gif /home/askpeek/images/traffic/traffic.gif");
      system("convert -resize 640x -format jpg-baseline -quality 75 /home/askpeek/images/traffic/traffic.gif /home/askpeek/images/traffic/traffic.jpg; rm /home/askpeek/images/traffic/traffic.gif");
      system("convert -crop 640x448+0+0 +repage /home/askpeek/images/traffic/traffic.jpg /home/askpeek/images/traffic/t1.jpg");
      system("convert -crop 640x448+0+448 +repage /home/askpeek/images/traffic/traffic.jpg /home/askpeek/images/traffic/t2.jpg");
      sendMessage($who, "Traffic Results", "Check the attachment for a map", array("/home/askpeek/images/traffic/t1.jpg", "/home/askpeek/images/traffic/t2.jpg"), "Traffic <traffic@retroforth.org>");
    }

    if ($type != "traffic")
    {
      $url = str_replace(" ", "%20", $message);
      $type = "traffic";
      $html = get_url_contents("http://local.yahooapis.com/MapsService/rss/trafficData.xml?appid=T.Rh_I7V34EkQWhUSSdqJrUq3HeGLbXjLhCV4F3syQgpbLIwA1jj9lfVAwjzH5dJ2brChLnUgQ--&location=" . $url);
      $xml  = new SimpleXMLElement(utf8_decode($html));
      foreach ($xml->channel->item as $item)
      {
        $body .= $item->title."\n\n";
      }
  $body = $body . "\n-----\nThis is a free service provided by Charles Childers. If you find it useful,";
  $body = $body . " please consider making a small donation to help offset costs at https://www.wepay.com/donate/49365";
      $header = "From: Traffic <traffic@askpeek,com>\r\n";
      mail($who, "Traffic Results" . $subject, $body, $header);
    }
}



function ask_weather()
{
  global $message, $who, $type, $subject, $body;
  $url = str_replace(" ", "%20", $message);
  $body = get_url_contents("http://retroforth.org/eds/weather.php?query=" . $url);

/*
      $ran = rand();
      $target = "/tmp/".$ran.".gif";
      $src = get_url_contents("http://api.wunderground.com/auto/wui/geo/GeoLookupXML/index.xml?query=".$url);
      if ($src != "")
      {
        $xml = new SimpleXMLElement($src);
        download($target, $xml->radar->image_url);
        sendMessage($who, "Weather for " . $message, $body, array("/home/askpeek/images/weather/radar.jpg", "/home/askpeek/images/weather/IR.jpg", "/home/askpeek/images/weather/satellite.jpg", "/home/askpeek/images/weather/watervapor.jpg", "/home/askpeek/images/weather/temp.jpg", $target), "Weather <weather@retroforth.org>");
        unlink($target);
      }
      else
      {
*/
  $body = $body . "\n-----\nThis is a free service provided by Charles Childers. If you find it useful,";
  $body = $body . " please consider making a small donation to help offset costs at https://www.wepay.com/donate/49365";
       sendMessage($who, "Weather for " . $message, $body, array("/home/askpeek/images/weather/radar.jpg", "/home/askpeek/images/weather/IR.jpg", "/home/askpeek/images/weather/satellite.jpg", "/home/askpeek/images/weather/watervapor.jpg", "/home/askpeek/images/weather/temp.jpg"), "Weather <weather@retroforth.org>");
//      }
}



function ask_howto()
{
  global $message, $who, $type, $subject, $body;
  $body  = "Need Information? Ent is here to help!\n\nYour Peek comes preloaded with some handy contacts ";
  $body .= "to provide you with access to a variety of useful information. To use these services, ";
  $body .= "send a message with the request in the subject line.\n\n";
  $body .= "Local Search\nGoogle Local Search. Try something like: Pizza near Oakford, PA\n\n";
  $body .= "News Headlines\nGet the top news stories from NPR. You can leave the subject blank, or specifiy ";
  $body .= "one of the following for other sources: us, world, bbc, ny times\n\n";
  $body .= "Maps\nLost? Send an address in the subject to get a map of the area.\n\n";
  $body .= "Traffic\nGet traffic updates for your area. Send the city, state in the subject to check for accidents and delays. If you're in California, you can get also get traffic maps. Send a message with a subject containing ";
  $body .= "one of the following: LA East, LA West, SF (for San Fransisco), SD (For San Diego), or Inland Empire.\n\n";
  $body .= "Weather\nYou can get a current weather forecast by sending the zipcode or City, State to this address.\n\n";
  $body = $body . "\n-----\nThis is a free service provided by Charles Childers. If you find it useful,";
  $body = $body . " please consider making a small donation to help offset costs at https://www.wepay.com/donate/49365";
  $header = "From: Howto <howto@retroforth.org>\r\n";
  mail($who, "Ent Help" . $subject, $body, $header);
}

?>
