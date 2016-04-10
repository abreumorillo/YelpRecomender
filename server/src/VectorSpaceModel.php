<?php

namespace  YRS;

/**
 *@purpose         : This class is used for building the index  using the the Vector Space Model algorithm.
 *@course          : Knowledge Processing Technologies.
 *
 *@author          : Group #
 *
 *@version         : 1.0
 *
 *@see             : Parser.php, StemTokenizer.php, Document.php
 */
class VectorSpaceModel
{
    /**
     * Collection of documents.
     *
     * @var array
     */
    private $documents;

    private $index = [];

    /**
     * Store the number of documents.
     *
     * @var int
     */
    private $numberOfDocuments = 0;

    // /**
    //  * index of term
    //  * @var array
    //  */
    // private $termList;

    // /**
    //  * Postings list
    //  * @var array
    //  */
    // private $docLists;

    private $restaurants = array();

    private $docLength = array();
    private $scores = array();

    public function __construct($docs)
    {
        $this->documents = $docs;
        $this->buildIndex();
    }

    private function buildIndex()
    {
        $index = array();
        $docCount = array();
        foreach ($this->documents as $docId => $doc) {
            //Get the tokens for each document, as part of the token normalization process we do stemming and casefolding @see StemTokenizer
            $tokens = StemTokenizer::getTokens($doc->content);
            //As in PHP array are dinamic we need to initialize the length of each document and scores with zero, otherwise we will get error calculating the length of the document, we would get Undefined offset: N, where N is the document.
            $this->docLength[$docId] = 0;
            $this->scores[$docId] = 0;
            //$docCount[$docId] = count($tokens);
            $this->numberOfDocuments++;
            $this->restaurants[$docId] = $doc; //This array contains the restaurants objects,

            foreach ($tokens as $token) {
                if (!isset($this->index[$token])) {
                    $this->index[$token] = array('df' => 0, 'postings' => array());
                }
                if (!isset($this->index[$token]['postings'][$docId])) {
                    $this->index[$token]['df']++;
                    $this->index[$token]['postings'][$docId] = array('tw' => 0);
                }
                $this->index[$token]['postings'][$docId]['tw']++;
            }
        }

        //Calculate the TF-IDF and partial calculation of the document length
        foreach ($this->index as $token => $postingsList) {
            foreach ($postingsList['postings'] as $docId => $document) {
                $tfidf = (1 + log10($document['tw'])) * log10($this->numberOfDocuments / floatval($postingsList['df']));
                $this->docLength[$docId] += pow($tfidf, 2); //First part of the document length calculation
                $this->index[$token]['postings'][$docId]['tw'] = $tfidf;
            }
        }

        // Compute the actual document length  |x|=sqrt(x_a^2 + x_b^2 + x_n^2).
        foreach ($this->docLength as $docId => $length) {
            $this->docLength[$docId] = sqrt($length);
        }
        // self::debugResult($this->docLength);
    }

    public function search($searchString)
    {
        $query = explode(' ', $searchString);
        foreach ($query as $term) {
            $entry = $this->index[$term];
            $queryTfIdf =  log10(floatval($this->numberOfDocuments)/floatval($entry['df']));//(1+log10(1)) * log10($this->numberOfDocuments/floatval($entry['df']));
            // self::debugResult($queryTfIdf);
            foreach ($entry['postings'] as $docId => $posting) {
                $this->scores[$docId] += $queryTfIdf * $posting['tw'];
            }
        }
        foreach ($this->scores as $docId => $score) {
            $this->scores[$docId] = $score/$this->docLength[$docId];
        }
        self::debugResult($this->scores);
    }

        //     $entry = $index['dictionary'][$term];
        // foreach($entry['postings'] as  $docID => $postings) {
        //         echo "Document $docID and term $term give TFIDF: " .
        //                 ($postings['tf'] * log($docCount / $entry['df'], 2));
        //         echo "\n";
        // }

        // self::debugResult($this->index);
    /**
     * Function use for debuggin purpose
     * @param  string $value
     * @return mix
     */
    public static function debugResult($value = '')
    {
        echo '<pre>';
        var_export($value);
        echo '</pre>';
    }
}
