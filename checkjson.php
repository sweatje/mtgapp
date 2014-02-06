<?php
define('CACHEDIR','cache/');
define('MTGJSON','http://mtgjson.com/json/');
define('VERFILE','version.json');
define('SETFILE','SetCodes.json');

if (!file_exists(CACHEDIR.VERFILE) || file_get_contents(CACHEDIR.VERFILE) !=  file_get_contents(MTGJSON.VERFILE) ) {
	file_put_contents(CACHEDIR.VERFILE,  file_get_contents(MTGJSON.VERFILE)) ;
	$sets_json =  file_get_contents(MTGJSON.SETFILE);
	file_put_contents(CACHEDIR.SETFILE,  $sets_json);
	foreach(json_decode( $sets_json ) as $set ) {
		file_put_contents(CACHEDIR.$set.'.json',file_get_contents(MTGJSON.$set.'.json'));
	}
}
