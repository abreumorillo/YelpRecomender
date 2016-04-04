<?php
namespace  YRS;
use YRS\Parser;
use YRS\StemTokenizer;
/**
*@purpose         : This class is used for building the index  using the the Vector Space Model algorithm.
*@course          : Knowledge Processing Technologies.
*@author          : Group #
*@version         : 1.0
*@see             : Parser.php, StemTokenizer.php, Document.php
*
*/
class VectorSpaceModel
{
    /**
     * Collection of documents
     * @var array
     */
    private $documents;

    /**
     * Dictionary of term
     * @var array
     */
    private $termList;

    /**
     * Postings list
     * @var array
     */
    private $docLists;

    private $restaurants = array();

    private $docLength = array();


    function __construct($docs)
    {
        $this->documents = $docs;
        $this->buildIndex();
    }

    private function buildIndex()
    {
        $dictionary = array();
        $docCount = array();
        foreach ($this->documents as $docId => $doc) {
            $tokens = StemTokenizer::getTokens($doc['content']);
            $docCount[$docId] = count($tokens);

            $restaurants[] = array(
                'docId' => $docId,
                'title' => $doc['title'],
                'city' => $doc['city'],
                'state' => $doc['state']);

            foreach ($tokens as $token) {
                if(!isset($dictionary[$token])) {
                    $dictionary[$token] = array('df'=> 0, 'postings' =>array());
                }
                if(!isset($dictionary[$token]['postings'][$docId])) {
                    $dictionary[$token] ['df']++;
                    $dictionary[$token]['postings'][$docId] =  array('tw' => 0);
                }
                $dictionary[$token]['postings'][$docId]['tw']++;
            }
        }

        //Calculate the TF-IDF
        foreach ($this->index['dictionary']['token'] as $key => $token) {
            foreach ($token['posting'] as $docId => $postings) {
                $tfidf= (1 + log10($postings['tw'])) * log10($docCount/$token['df']);
                $this->docLength[$docId] =  pow(tfidf, 2);
                $this->index['dictionary']['postings'][$docId]['tw'] = $tfidf;
            }
        }

        //Document length
        foreach ($this->docLength as $docId => $length) {
            $this->docLength[$docId] = sqrt($length);
        }

        $this->index = array(
            'docCount'      => $docCount,
            'dictionary'    => $dictionary
            );
    }
}