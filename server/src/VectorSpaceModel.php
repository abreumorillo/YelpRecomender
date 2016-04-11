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

    /**
     * Hold the Index.
     *
     * @var array
     */
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

    /**
     * This array stores the restaurants objects, this objects are used to return useful information
     * to the user.
     *
     * @var array
     */
    private $restaurants = array();

    /**
     * Store the length of the documents for normalization purpose.
     *
     * @var array
     */
    private $docLength = array();

    /**
     * Store the score for each document.
     *
     * @var array
     */
    private $scores = array();

    /**
     * Maximun number of result to be return.
     *
     * @var int
     */
    const TOP_RESULT = 10;

    /**
     * Build the index.
     *
     * @param array $docs
     */
    public function __construct($docs)
    {
        $this->documents = $docs;
        $this->buildIndex();
    }

    /**
     * Build the index and calculate the TF-IDF.
     *
     * @return mix
     */
    private function buildIndex()
    {
        $docCount = array();
        foreach ($this->documents as $docId => $doc) {
            //Get the tokens for each document, as part of the token normalization process we do stemming and casefolding @see StemTokenizer
            $tokens = StemTokenizer::getTokens($doc->content);
            //As in PHP array are dinamic we need to initialize the length of each document and scores with zero, otherwise we will get error calculating the length of the document, we would get Undefined offset: N, where N is the document.
            $this->docLength[$docId] = 0;
            $this->scores[$docId] = 0;
            //$docCount[$docId] = count($tokens);
            ++$this->numberOfDocuments;
            $this->restaurants[$docId] = $doc;

            foreach ($tokens as $token) {
                if (!isset($this->index[$token])) {
                    $this->index[$token] = array('df' => 0, 'postings' => array());
                }
                if (!isset($this->index[$token]['postings'][$docId])) {
                    ++$this->index[$token]['df'];
                    $this->index[$token]['postings'][$docId] = array('tw' => 0);
                }
                ++$this->index[$token]['postings'][$docId]['tw'];
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

    /**
     * Handle the cosine score search.
     *
     * @param string $searchString
     *
     * @return array top k ranked documents
     */
    public function search($searchString)
    {
        $result = [];
        $query = explode(' ', $searchString);
        foreach ($query as $term) {
            if (key_exists($term, $this->index)) {
                $entry = $this->index[$term];
                $queryTfIdf = log10(floatval($this->numberOfDocuments) / floatval($entry['df']));//(1+log10(1)) * log10($this->numberOfDocuments/floatval($entry['df']));
                foreach ($entry['postings'] as $docId => $posting) {
                    $this->scores[$docId] += $queryTfIdf * $posting['tw'];
                }
            }
        }

        /*
         * We use a heap datastructure for retrieven top K documents
         * @var SearchScoreHeap
         */
        $scoreHeap = new SearchScoreHeap();
        foreach ($this->scores as $docId => $score) {
            $normalizedScore = $score / $this->docLength[$docId];
            $this->scores[$docId] = $normalizedScore;
            $scoreHeap->insert([$docId => $normalizedScore]);
        }

        $scoreHeap->top();
        $topResult = 0;
        // Retrieving the top k document order by score, documents are returned in descending order
        while ($scoreHeap->valid() && $topResult < self::TOP_RESULT) {
            $value = $scoreHeap->current();
            $docId = key($value);
            $docScore = $value[$docId];
            if ($docScore > 0) {
                $restaurant = $this->restaurants[$docId];
                $restaurant->score = $docScore;//We add the score to the object restaurant.
                $result[] = $restaurant;
            }
            $scoreHeap->next();
            ++$topResult;
        }

        return $result;
    // self::debugResult($result);
// self::debugResult($this->index);
    }

    /**
     * Function use for debuggin purpose.
     *
     * @param string $value
     *
     * @return mix
     */
    public static function debugResult($value = '')
    {
        echo '<pre>';
        var_export($value);
        echo '</pre>';
    }
}
