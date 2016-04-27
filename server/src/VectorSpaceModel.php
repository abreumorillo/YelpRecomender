<?php

namespace  YRS;

/**
 *@purpose         : This class is used for building the index  using the the Vector Space Model algorithm.
 *@course          : Knowledge Processing Technologies.
 *
 *@author          : Group #6
 *
 *@version         : 1.0
 *
 *@see             : Parser.php, Tokenizer.php, Document.php
 */
class VectorSpaceModel implements SaveToFileInterface
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

    private $classifier;

    /**
     * Build the index.
     *
     * @param array $docs
     */
    public function __construct()
    {
        if(file_exists(SaveToFileInterface::INDEX_FILE_NAME)) {
            $this->getDataFromFile();
            $this->classifier = new NaiveBayesClassifier;
            return;
        }
        $parser = new Parser();
        $this->classifier = new NaiveBayesClassifier;
        $this->classifier->trainClassifier();
        $this->documents = $parser->getDocuments();
        $this->buildIndex();
        $this->saveDataToFile();
    }

    public function saveDataToFile()
    {
        $dataToSave['index'] = $this->index;
        $dataToSave['restaurants'] = $this->restaurants;
        $dataToSave['docLength'] = $this->docLength;
        $dataToSave['scores'] = $this->scores;
        $dataToSave['numberOfDocuments'] = $this->numberOfDocuments;

        $jsonIndex = json_encode($dataToSave);

        file_put_contents(SaveToFileInterface::INDEX_FILE_NAME, $jsonIndex);
    }

    public function getDataFromFile()
    {
        $indexFromFile = json_decode(file_get_contents(SaveToFileInterface::INDEX_FILE_NAME));

        $this->index = json_decode(json_encode($indexFromFile->index), true);//$indexFromFile->index;
        $this->restaurants = $indexFromFile->restaurants;//json_decode(json_encode($indexFromFile->restaurants),true);
        $this->docLength = json_decode(json_encode($indexFromFile->docLength), true);
        $this->scores = json_decode(json_encode($indexFromFile->scores), true);
        $this->numberOfDocuments = $indexFromFile->numberOfDocuments;

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
            //Get the tokens for each document, as part of the token normalization process we do stemming and casefolding @see Tokenizer
            $document = "$doc->content $doc->businessName"; //Appending review and business name
            $tokens = Tokenizer::getTokens($document);
            //As in PHP array are dinamic we need to initialize the length of each document and scores with zero, otherwise we will get error calculating the length of the document, we would get Undefined offset: N, where N is the document.
            $this->docLength[$docId] = 0;
            $this->scores[$docId] = 0;
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

        //Add token count information to statistic class
        ProjectStatistic::setTokenCount(Tokenizer::getTokensCount());
        ProjectStatistic::setSpellingVocabularySize(Tokenizer::getVocabularySize());

        new SpellChecker(Tokenizer::getRawToken());

        //Calculate the TF-IDF and partial calculation of the document length
        foreach ($this->index as $token => $postingsList) {
            foreach ($postingsList['postings'] as $docId => $document) {
                $tfidf = (1 + log($document['tw'])) * log(floatval($this->numberOfDocuments) / floatval($postingsList['df']));
                $this->docLength[$docId] += pow($tfidf, 2); //First part of the document length calculation
                $this->index[$token]['postings'][$docId]['tw'] = $tfidf;
            }
        }
        // Compute the actual document length  |x|=sqrt(x_a^2 + x_b^2 + x_n^2).
        foreach ($this->docLength as $docId => $length) {
            $this->docLength[$docId] = sqrt($length);
        }
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
        $search = strtolower($searchString);
        $query = explode(' ', $searchString);
        $queryLength = 0.0;


        foreach ($query as $term) {
            $term = PorterStemmer::Stem($term);
            if (key_exists($term, $this->index)) {
                $entry = $this->index[$term];
                $queryTfIdf = log(floatval($this->numberOfDocuments) / floatval($entry['df']));
                $queryLength += pow($queryTfIdf, 2);
                foreach ($entry['postings'] as $docId => $posting) {
                    $this->scores[$docId] += $queryTfIdf * $posting['tw'];
                }
            }
        }

        //calculating  query length, if no length we return an empty array
        $queryLength = sqrt($queryLength);
        if ($queryLength <= 0.0) {
            return $result;
        }


        /*
         * We use a heap datastructure for retrieven top K documents
         * @var SearchScoreHeap
         */
        $scoreHeap = new SearchScoreHeap();

        foreach ($this->scores as $docId => $score) {
            $normalizedScore = $score  / ($queryLength * $this->docLength[$docId]); //Full normalization
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

        foreach ($result as $document) {
            $group = $this->classifier->classify($document->content);
            $class = $this->classifier->getClass($group);
            $document->classProbability = $group;
            $document->class = $class;
        }

        return $result;
    }
}
