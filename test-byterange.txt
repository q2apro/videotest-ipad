<?php
// disable zlib so that progress bar of player shows up correctly
if(ini_get('zlib.output_compression')) {
	ini_set('zlib.output_compression', 'Off'); 
}

$folder = '.'; 
$filename = 'video.mp4';
$path = $folder.'/'.$filename;

// from http://stackoverflow.com/questions/17966493/show-video-on-iphone-ipad-through-php
if (file_exists($path)) {
	$size=filesize($path);
	$fm=@fopen($path,'rb');
	if(!$fm) {
		header ("HTTP/1.0 404 Not Found");
		die();
	}
	$begin = 0;
	$end = $size;
	if(isset($_SERVER['HTTP_RANGE'])) {
		if(preg_match('/bytes=\h*(\d+)-(\d*)[\D.*]?/i',   
			$_SERVER['HTTP_RANGE'],$matches)){
			$begin=intval($matches[1]);
			if(!empty($matches[2])) {
				$end=intval($matches[2]-1);
			}
		}
	}
	if($begin>0||$end<$size) {
		header('HTTP/1.0 206 Partial Content');
	}
	else {
		header('HTTP/1.0 200 OK');
	}
	header("Content-Type: video/mp4");
	header('Accept-Ranges: bytes');
	header('Content-Length:'.($end-$begin));
	header("Content-Disposition: inline;");
	header("Content-Range: bytes $begin-$end/$size");
	header("Content-Transfer-Encoding: binary\n");
	header('Connection: close');
	$cur = $begin;
	fseek($fm, $begin, 0);
	while(!feof($fm)&&$cur<$end&&(connection_status()==0)) { 
		print fread($fm,min(1024*16,$end-$cur));
		$cur+=1024*16;
		usleep(1000);
	}
	die();
}
die();	

?>