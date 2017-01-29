<?php

require_once __DIR__.'/../vendor/autoload.php';

use YnotnA\Igdb\IgdbApi;

define('API_KEY', 'Your_Mashape_Key');

$client = new IgdbApi(API_KEY);

// see https://igdb.github.io/api/endpoints/ for used fields.
$games = $client->getGames('mario', array('name'), 10, 0);

var_dump($games);