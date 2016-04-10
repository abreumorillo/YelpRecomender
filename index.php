<?php
require_once('bootstrapper.inc');
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use YRS\Parser;
use YRS\StemTokenizer;
use YRS\VectorSpaceModel;

$app = new \Slim\App;

/*
    Simple RESTful API for the application
*/

$app->get('/api/restaurants/{name}', function (Request $request, Response $response) {
    $name = $request->getAttribute('name');
    $parser = new Parser();
    $test = $parser->getDocuments();
    return $response->withJSON(['info'=> "Hello, $name $test"]);
});

// $app->run();

//StemTokenizer testing
// $document = "The ponies abandoment in the universe";
// var_dump(StemTokenizer::getTokens($document));

// echo json_encode(['greeting'=>'Hello']);
// $fileContent = file_get_contents("data/rest_review5.txt");
// $fileContent = file_get_contents("data/restaurant_review4.json");
// $json = json_decode($fileContent);
// print_r($json->content);
// $firstLine = explode("\n", $fileContent);

// $json = '{"a":1,"b":2,"c":3,"d":4,"e":5}';

// var_dump(json_decode($json));
// var_dump(json_decode($json, true));

// foreach ($firstLine as $line) {
//     echo $line.'<br>';
// }

// $files = scandir('data/');



//Testing file reading files -- Worked!
//  $dir = "data/";

// // Open a known directory, and proceed to read its contents
// if (is_dir($dir)) {
//     if ($dh = opendir($dir)) {
//         while (($file = readdir($dh)) !== false) {
//             echo "filename: $file : filetype: " . filetype($dir . $file) . "<br>";
//         }
//         closedir($dh);
//     }
// }

// $docId = 0;
// $documents =[];
// foreach (glob("data/*.txt") as $filename) {
//     // echo "$filename size " . filesize($filename) . "<BR>";
//     $documents[$docId] = file($filename);
//     $docId++;
// }
// echo 'HELLO';
// echo '<h1>Vector Space Model </h1><br>';
$parser = new Parser();
$documents = $parser->getDocuments();
$vsm = new VectorSpaceModel($documents);
$vsm->search("excel good food");
// foreach (glob("data/*.json") as $key => $file) {
//     $now = microtime();
//     // echo substr($filename, 5, strrpos($filename, '.')-5) .'<br>';
//     $document = json_decode(file_get_contents($file)); //True for returning content as associative array
//     preg_match('/\/(.*?)\./', $file, $match);
//     $document->fileName= $match[1];
//     $documents[] = $document;
// }
// echo $now;
 // var_dump($documents);

// foreach ($documents as $document) {
//     echo '--------------------------------------------'.'<BR>';
//     echo $document->businessName.'<BR>';
//     echo $document->content.'<BR>';
// }