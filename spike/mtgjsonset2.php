<?php
require_once('../model/Hash.php');

$h = new Hash(json_decode(file_get_contents('http://mtgjson.com/json/PPR.json')));
$cards = [];
foreach($h->cards as $card) $cards[] = new Hash($card);
$h->cards = new Hash($cards);
var_dump($h);

