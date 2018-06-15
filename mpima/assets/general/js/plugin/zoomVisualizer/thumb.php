<?php
   // by dooobd@NOSPAM.doobd.com
   //
   // proportional on-the-fly thumb generator from JPG images
   //
   // usage example:
   // <img src= "thumb.php?src=pic.jpg&wmax=150&hmax=100&quality=90&bgcol=FF0000"> </img>
   //
   // parameters:  src = source image
   //              wmax = max width
   //              hmax = max height
   //              quality = JPG quality of generated thumb - optional.
   //                        if not specified, quality=90
   //              bgcol = if specified, allways generates exact wmax x hmax sized thumb,
   //                      with bacground color bgcol and centered source image
   //
   // note: if source image is smaller than desired thumbnail, it will not be resized!
	 
	ini_set ("display_errors","1");
	
	$srcX = 0;
	$srcY = 0;
	$quality = ($_GET['quality']) ? $_GET['quality'] : 100;
	
	list($width, $height, $type, $attr) = getimagesize($_GET["src"]);
	
	switch($type)
	{
		case 1:
			header("Content-type: image/gif");
			$source = imagecreatefromgif($_GET["src"]);
			break;
		case 2:
			header("Content-type: image/jpeg");
			$source = imagecreatefromjpeg($_GET["src"]);
			break;
		case 3:
			header("Content-type: image/png");
			$source = imagecreatefrompng($_GET["src"]);
			imagealphablending($source,true);
			imagesavealpha($source,true);
			break;
	}
	
	$wPct = $_GET["wmax"]/$width;
	$hPct = $_GET["hmax"]/$height;
	
	$pctMin = min($wPct,$hPct);
	$pctMax = max($wPct,$hPct);
	$pct = ($_GET['crop']) ? $pctMax : $pctMin;

	if($pct < 1)
	{
		$thumb_w = round($width*$pct);
		$thumb_h = round($height*$pct);
	}
	else
	{
		$thumb_w = $width;
		$thumb_h = $height;
		
		if($_GET['crop'] && !$_GET['bgcol'])
		{
			$_GET['bgcol'] = "FFFFFF";
		}
	}
	
	if($_GET['crop'])
	{
		$wThumb = $_GET["wmax"];
		$hThumb = $_GET["hmax"];
		
		$srcX = ($wThumb-$thumb_w)/2;
		
		if($_GET['crop'] == "top")
		{
			$srcY = ($pct < 1) ? 0 : ($hThumb-$thumb_h)/2;
		}
		else
		{
			$srcY = ($hThumb-$thumb_h)/2;
		}
		
	}
	else
	{
		$wThumb = $thumb_w;
		$hThumb = $thumb_h;
		
		if($_GET['bgcol'])
		{
			$wThumb = $_GET["wmax"];
			$hThumb = $_GET["hmax"];
			$srcX = round(($_GET["wmax"]-$thumb_w)/2);
			$srcY = round(($_GET["hmax"]-$thumb_h)/2);
		}
	}
			
	$thumb = imagecreatetruecolor($wThumb,$hThumb);
	
	if($_GET["bgcol"])
	{
		imagefilledrectangle($thumb,0,0,$_GET["wmax"]-1,$_GET["hmax"]-1,intval($_GET["bgcol"],16));
	}
	
	imagecopyresampled($thumb,$source,$srcX,$srcY,0,0,$thumb_w,$thumb_h,$width,$height);


	switch($type)
	{
		case 1:
			imagegif($thumb,"",$quality);
			break;
		case 2:
			imagejpeg($thumb,"",$quality);
			break;
		case 3:
			imagepng($thumb);
			break;
	}

	imagedestroy($thumb);
		 
?>