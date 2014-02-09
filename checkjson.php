<?php
require_once 'pdo.php';

define('CACHEDIR','cache/');
define('MTGJSON','http://mtgjson.com/json/');
define('VERFILE','version.json');
define('SETFILE','SetCodes.json');

if (!file_exists(CACHEDIR.VERFILE) || file_get_contents(CACHEDIR.VERFILE) !=  file_get_contents(MTGJSON.VERFILE) ) {
	$db = new Model(db());
	file_put_contents(CACHEDIR.VERFILE,  file_get_contents(MTGJSON.VERFILE)) ;
	$sets_json =  file_get_contents(MTGJSON.SETFILE);
	file_put_contents(CACHEDIR.SETFILE,  $sets_json);
	foreach(json_decode( $sets_json ) as $set ) {
		$setjson = file_get_contents(MTGJSON.$set.'.json');
		file_put_contents(CACHEDIR.$set.'.json',$setjson);
		$cset = json_decode( $setjson );
		$fields = ['id'=>$cset->code,'name'=>$cset->name,'rls_dt'=>$cset->releaseDate];
		var_dump($fields);
		$db->upsert('cset','id',$fields);
	}
}


