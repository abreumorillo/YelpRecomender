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
    // private static $splitRegex = "/[\s\"\.,:;&%~^+$\(\)\$#!\?\/\\\-]+/";
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
        $tokens = self::processTokens($document);
        foreach ($tokens as $id => $token) {
            if (strlen($token) > 0) {
                $tokens[$id] = PorterStemmer::Stem($tokens[$id]); //Apply the stemmer to each term.
                //Add to raw tokens array for spelling correction.
                if (!in_array($token, self::$rawTokens)) { // && strlen($token) > 1
                    self::$rawTokens[] = $token;
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

    /**
     * Process initial tokens
     * @param  string  $document
     * @return array
     */
    public static function processTokens($document)
    {
        $document = strtolower($document);
        $string = preg_replace('/[^a-z0-9 ]/', '', $document);
        $string = preg_replace('/[0-9]/', ' ', $document);
        $count = preg_match_all('/\w+/', $string, $matches);
        if($count) {
            return $matches[0];
        }
        return [];
    }
}
