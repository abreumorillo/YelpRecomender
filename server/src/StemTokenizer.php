<?php

namespace YRS;

/**
 * @purpose         : This class is responsible for generating the stemmed tokens given a document.
 * @course          : Knowledge Processing Technologies ISTE-612
 * @author          : Group
 * @version         : 1.0
 * @see             : PorterStemmer.php
 */
class StemTokenizer
{
    /**
     * Regex for splitting the document //old //"/[\s\"\.,:;&%~^+\(\)\$#!\?\/\\\-]+/";.
     * @var string
     */
    private $splitRegex = "/[\s\"\.,:;&%~^+$\(\)\$#!\?\/\\\-]+/";

    /**
     * This method get the stemmed tokens from a given document.
     * @param string $document
     * @return array
     */
    public static function getTokens($document)
    {
        //Split the document based on the regular expresion.
        $tokens = preg_split($this->splitRegex, $document);
        //$tokenCount = count($tokens);
        //Tokenize each word
        // for ($i = 0; $i < $tokenCount; ++$i) {
        //     $tokens[$i] = PorterStemmer::Stem($tokens[$i]); //Apply the stemmer to each term.
        // }
        foreach ($tokens as $id => $token) {
            $tokens[$id] = PorterStemmer::Stem($tokens[$i]); //Apply the stemmer to each term.
        }
        return $tokens;
    }
}
