<?php

namespace YRS;

/**
 * @purpose         : This class is responsible for generating the stemmed tokens given a document.
 * @course          : Knowledge Processing Technologies ISTE-612
 *
 * @author          : Group # 6
 *
 * @version         : 1.0
 *
 * @see             : PorterStemmer.php
 */
class Tokenizer
{
    /**
     * Regex for splitting the document //old //"/[\s\"\.,:;&%~^+\(\)\$#!\?\/\\\-]+/";.
     *
     * @var string
     */
    private static $splitRegex = "/[\s\"\.,:;&%~^+$\(\)\$#!\?\/\\\-]+/";
    private static $rawTokens = array();

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
                //Add to raw tokens array for spelling correction.
                if (!in_array(strtolower(trim($token)), self::$rawTokens) && strlen($token) > 1) {
                    self::$rawTokens[] = strtolower(trim($token));
                }
            } else {
                unset($tokens[$id]);
            }
        }

        return $tokens;
    }

    /**
     * Get the raw tokens for building dictionary, the purpose of the dictionary is to be used
     * for spelling correction
     * @return array
     */
    public static function getRawToken()
    {
        sort(self::$rawTokens);

        return self::$rawTokens;
    }
}
