<?php

require_once 'bootstrapper.inc';

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use YRS\VectorSpaceModel;
use YRS\SpellChecker;
use YRS\ProjectStatistic;

//Create new application. We are using SLIM3 for building this data API
$app = new \Slim\App();

//Restaurant API URL: /api/restaurants/searchString
$app->get('/api/restaurants/{searchString}', function (Request $request, Response $response) {
    $vsm = new VectorSpaceModel();
    $searchString = $request->getAttribute('searchString');
    $result = $vsm->search($searchString);
    if (count($result) <= 0) {
        return $response->withJSON(['message' => 'Not data found'], 404);
    }

    return $response->withJSON($result);

});

//Spell Checker URL: /api/spellchecker/word
$app->get('/api/spellchecker/{term}', function (Request $request, Response $response) {
    $term = $request->getAttribute('term');
    if (strlen($term) <= 2) {
        return $response->withJSON([]);
    }
    $spellChecker = new SpellChecker;
    $result = $spellChecker->check($term);

    return $response->withJSON($result);
});
//Initializes the application and get general statistics. URL: /api/getStatistics
$app->get('/api/getStatistics', function (Request $request, Response $response) {
    new VectorSpaceModel();
    $statistics = ProjectStatistic::getStatistics();
    return $response->withJSON($statistics);
});

$app->run();
