<?php

function getIndex()
{
    $collection = array(
        1 => 'this string is a short string but a good string',
        2 => 'this one isn\'t quite like the rest but is here',
        3 => 'this is a different short string that\' not as short',
        );

    $dictionary = array();
    $docCount = array();

    foreach ($collection as $docID => $doc) {
        $terms = explode(' ', $doc);
        $docCount[$docID] = count($terms);

        foreach ($terms as $term) {
            if (!isset($dictionary[$term])) {
                $dictionary[$term] = array('df' => 0, 'postings' => array());
            }
            if (!isset($dictionary[$term]['postings'][$docID])) {
                ++$dictionary[$term]['df'];
                $dictionary[$term]['postings'][$docID] = array('tf' => 0);
            }

            $dictionary[$term]['postings'][$docID]['tf']++;
        }
    }

    return array('docCount' => $docCount, 'dictionary' => $dictionary);
}

function getTfidf($term)
{
    $index = getIndex();
    $docCount = count($index['docCount']);
    $entry = $index['dictionary'][$term];
    foreach ($entry['postings'] as  $docID => $postings) {
        echo "Document $docID and term $term give TFIDF: ".
        ($postings['tf'] * log10($docCount / $entry['df']));
        echo "\n";
    }
}

$query = array('is', 'short', 'string');

$index = getIndex();

var_dump($index);

$matchDocs = array();
$docCount = count($index['docCount']);

foreach ($query as $qterm) {
    $entry = $index['dictionary'][$qterm];

    foreach ($entry['postings'] as $docID => $posting) {
        var_dump($posting);
        $matchDocs[$docID] =
        (1 + log10($posting['tf'])) *
        log10($docCount + 1 / $entry['df']);
    }
}

// length normalise
foreach ($matchDocs as $docID => $score) {
    $matchDocs[$docID] = $score / $index['docCount'][$docID];
}

arsort($matchDocs); // high to low

var_dump($matchDocs);
