<?php
require_once('bootstrapper.inc');
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use YRS\Parser;
use YRS\StemTokenizer;

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

$document = "The ponies abandoment in the universe";
var_dump(StemTokenizer::getTokens($document));

echo '<h1>New changes</h1>';
// echo json_encode(['greeting'=>'Hello']);
// $fileContent = file_get_contents("data/rest_review5.txt");
// $fileContent = file_get_contents("data/restaurant_review4.json");
// $json = json_decode($fileContent);
// var_dump($json);
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

// $documents =[];
// foreach (glob("data/*.txt") as $key => $filename) {
//     // echo "$filename size " . filesize($filename) . "<BR>";
//     $documents[$key] = file($filename);
// }

// var_dump($documents);

// foreach ($documents as $docment) {
//     echo '--------------------------------------------'.'<BR>';
//     echo $docment.'<BR>';
// }