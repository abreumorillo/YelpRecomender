<?php

require_once 'bootstrapper.inc';

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use YRS\VectorSpaceModel;
use YRS\SpellChecker;
use YRS\ProjectStatistic;

$app = new \Slim\App();

$vsm = new VectorSpaceModel();

//Restaurant API URL: /api/restaurants/searchString
$app->get('/api/restaurants/{searchString}', function (Request $request, Response $response) use ($vsm) {

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

$app->get('/api/getStatistics', function () {
    $statistics = ProjectStatistic::getStatistics();
    var_dump($statistics);
});

$app->get('/api/initializeApp', function (Request $request, Response $response) {
    $vsm = new VectorSpaceModel();
    var_dump($vsm);
});

$app->run();
