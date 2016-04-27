<?php
require_once('../vendor/autoload.php');
use YRS\EnglishStopWords;
use YRS\NaiveBayesClassifier;
// use YRS\Parser;
//"/[\s\"\.,:;&%~^+$\(\)\$#!\?\/\\\-]+/";
$string ="This also works in PHP 5.2.2 (PCRE 7.0) and later, however * the above form is recommended for backwards compatibility */preg_match_all('/(?<name>\w+):";

$string = strtolower($string);
$string = preg_replace('/[^a-z0-9 ]/', '', $string);
$string = preg_replace('/[0-9]/', ' ', $string);
$count = preg_match_all('/\w+/', $string, $matches);

// var_dump($matches[0]);
// $stopWords = EnglishStopWords::get();

// if(in_array('very', $stopWords, true)){
//     var_dump('true');
// }


// S$parser = new Parser;


$classifier = new NaiveBayesClassifier();

$badReview = "i will never eat here my family and i just tried to order food to go on christmas eve an hour and a half before closing and we were rudely turned away first we called to see if they were open and they informed us that they were i told the woman on the phone that we were going to look over the menu and call right back when we called back  minutes later the woman on the phone now had an attitude and with the most disgusting tone ive ever encountered she quickly told me that they were busy and werent taking any more to go orders i said i dont understand we just called and were told you dont close until  it was  at the time she said oh and placed on hold for  minutes just to come by and quickly dismiss us again with a yeah sorry were not taking anymore orders ive never eaten here and cant speak on the food but i will never eat at a place that treats people this way especially on a holiday";

$goodReview = "the burger was decent not the best in pittsburgh like a lot of people say the steak salad is really big and the steak is cooked perfectly so no complaints there the tables are kinda small so you feel a little cramped in there wait thats a common thing here in pittsburgh that im slowly discovering overall not bad no real complaints";


// var_dump($parser->getTrainDocuments());
// var_dump($parser->getTestingDocuments());
// $trainingDocuments = $parser->getTrainDocuments();
// foreach ($trainingDocuments as $class => $document) {
//     $classifier->train($class, $document);
// }
//

// $classifier->trainClassifier();

// $groups1 = $classifier->classify($badReview);
// $groups2 = $classifier->classify($goodReview);

// var_dump($groups1);
// var_dump($groups2);


