<?php

require_once('bootstrapper.inc');

$fileContent = file_get_contents("data/cv000_29416.txt");
$firstLine = explode("\n", $fileContent);



// foreach ($firstLine as $line) {
//     echo $line.'<br>';
// }

// $files = scandir('data/');




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

$docId = 0;
$documents =[];
foreach (glob("data/*.txt") as $filename) {
    // echo "$filename size " . filesize($filename) . "<BR>";
    $documents[$docId] = file($filename);
    $docId++;
}

var_dump($documents);

// foreach ($documents as $docment) {
//     echo '--------------------------------------------'.'<BR>';
//     echo $docment.'<BR>';
// }