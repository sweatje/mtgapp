<?php
require_once 'pdo.php';

define('CACHEDIR','cache/');
define('SETFILE','SetCodes.json');

$db = new Model(db());
$sets_json =  file_get_contents(CACHEDIR.SETFILE);
foreach(json_decode( $sets_json ) as $set ) {
	$set_json = file_get_contents(CACHEDIR.$set.'.json');
	$cset = json_decode( $set_json );
	$setfields = ['id'=>$cset->code,'name'=>$cset->name,'rls_dt'=>$cset->releaseDate];
	foreach($cset->cards as $card) {
		$fields = [
			 'id'=>$card->multiverseid
			,'name'=>$card->name
			,'cset'=>$set
			,'typeline'=>$card->type
			,'cost'=>@$card->manaCost
			,'cmc'=>@$card->cmc
			,'rarity'=>$card->rarity
			,'power'=>@$card->power
			,'toughness'=>@$card->toughness
			,'cardtext'=>@$card->text
			,'flavor'=>@$card->flavor
			,'imagename'=>$card->imageName
			,'preferred'=>$card->multiverseid
			,'original'=>$card->multiverseid
			];
		$db->upsert('card','id',$fields);
	}
}


