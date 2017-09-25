<?php
// disable zlib so that progress bar of player shows up correctly
if(ini_get('zlib.output_compression')) {
	ini_set('zlib.output_compression', 'Off'); 
}

$filename = 'test-normal-480p.mp4';

$vidfilesize = filesize($filename);

header("Accept-Ranges: bytes");
header("Content-Length: " . $vidfilesize);
header("Content-Type: video/mp4");
header("ETag: 3e34bf-e3914a-4fb02dc319cf8");
header("Last-Modified: Wed, 04 Jun 2014 13:46:49 GMT");
// load entire file
@readfile($filename);

?>