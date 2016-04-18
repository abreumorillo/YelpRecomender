<?php
require_once('../vendor/autoload.php');
use YRS\EnglishStopWords;
use YRS\NaiveBayesClassifier;
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

$classifier = new NaiveBayesClassifier();

$classifier->train('hot', 'The sun is hot');
$classifier->train('hot', 'It was a warm day in the sun');
$classifier->train('hot', 'This tea is hot!');

$classifier->train('cold', 'This ice is very cold!');
$classifier->train('cold', 'It\'s cold at night');
$classifier->train('cold', 'Ice formed on my at over night');

$classifier->train('Medium', 'It\'s cold at night');
$classifier->train('Medium', 'Ice formed on my at over night');

// var_dump($classifier);
$groups = $classifier->classify('It was chilly last night');
var_dump($groups);
