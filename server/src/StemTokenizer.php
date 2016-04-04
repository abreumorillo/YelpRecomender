<?php

namespace YRS;

use YRS\PorterStemmer;
/**
 * @purpose         : This class is responsible for generating the stemmed tokens given a document.
 * @course          : Knowledge Processing Technologies ISTE-612
 * @author          : Group
 * @version         : 1.0
 */
class StemTokenizer {

    /**
     * This method get the stemmed tokens from a given document
     * @param  String $document
     * @return Array
     */
    public static function getTokens($document)
    {
        //"/[\s\"\.,:;&%~^+\(\)\$#!\?\/\\\-]+/";
        $splitRegex = "/[\s\"\.,:;&%~^+$\(\)\$#!\?\/\\\-]+/";  //Regex for splitting the document
        //Split document into token
        $tokens = preg_split($splitRegex, $document);
        $tokenCount = count($tokens);
        //Tokenize each word
        for ($i=0; $i < $tokenCount; $i++) {
            $tokens[$i] = PorterStemmer::Stem($tokens[$i]);
        }
        return $tokens;
    }

}