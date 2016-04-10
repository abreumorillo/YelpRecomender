<?php

namespace YRS;

/**
 * @purpose         : This class is responsible for generating the stemmed tokens given a document.
 * @course          : Knowledge Processing Technologies ISTE-612
 *
 * @author          : Group
 *
 * @version         : 1.0
 *
 * @see             : PorterStemmer.php
 */
class StemTokenizer
{
    /**
     * Regex for splitting the document //old //"/[\s\"\.,:;&%~^+\(\)\$#!\?\/\\\-]+/";.
     *
     * @var string
     */
    private static $splitRegex = "/[\s\"\.,:;&%~^+$\(\)\$#!\?\/\\\-]+/";

    /**
     * This method get the stemmed tokens from a given document.
     *
     * @param string $document
     *
     * @return array
     */
    public static function getTokens($document)
    {
        //Split the document based on the regular expresion.
        $tokens = preg_split(self::$splitRegex, $document);
        foreach ($tokens as $id => $token) {
            if (strlen($token) > 0) {
                $tokens[$id] = PorterStemmer::Stem(strtolower($tokens[$id])); //Apply the stemmer to each term.
            }else {
                unset($tokens[$id]);
            }
        }
        return $tokens;
    }
}
