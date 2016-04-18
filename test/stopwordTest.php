<?php
require_once('../vendor/autoload.php');
use YRS\EnglishStopWords;
use YRS\Tokenizer;
use YRS\SpellChecker;
// $stopWords = new EnglishStopWords;
// var_dump(EnglishStopWords::get());
$tokenizer = new Tokenizer;
$document = "Excellent super food. Superb customer service. customer super service I miss the mario machines they used to have, but it's still.a great place steeped in tradition";
// var_dump(Tokenizer::getRawToken($document));

$spellChecker = new SpellChecker($document);

var_dump($spellChecker->check('excelent'));