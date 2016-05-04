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
set_time_limit(300);
class Tokenizer
{
    /**
     * Raw token for spelling correction vocabulary.
     *
     * @var array
     */
    private static $rawTokens = array();

    private static $stemmedTokens = array();

    /**
     * Class initializer.
     */
    public function __construct()
    {
    }

    /**
     * This method get the stemmed tokens from a given document.
     *
     * @param string $document
     *
     * @return array
     */
    public static function getTokens($document)
    {
        $result = [];
        $tokens = self::processTokens($document);
        $stopWords = EnglishStopWords::get();
        ProjectStatistic::setEnglishStopWordsCount(count($stopWords));
        foreach ($tokens as $token) {
            if (strlen($token) > 0) {
                $stemmedToken = PorterStemmer::Stem($token);

                if (!in_array($stemmedToken, $stopWords)) { //skip stopwords
                    $result[] = $stemmedToken;
                    if (!in_array($stemmedToken, self::$stemmedTokens)) {
                        self::$stemmedTokens[] = $stemmedToken;
                    }
                }

                // Add to raw tokens array for spelling correction.
                if (!in_array($token, self::$rawTokens)) {
                    self::$rawTokens[] = $token;
                }
            }
        }
        unset($tempRawTokens);

        return $result;
    }

    /**
     * Get the raw tokens for building dictionary, the purpose of the dictionary is to be used
     * for spelling correction.
     *
     * @return array
     */
    public static function getRawToken()
    {
        sort(self::$rawTokens);

        return self::$rawTokens;
    }

    public static function getVocabularySize()
    {
        return count(self::$rawTokens);
    }

    public static function getTokensCount()
    {
        return count(self::$stemmedTokens);
    }

    /**
     * Process initial tokens.
     *
     * @param string $document
     *
     * @return array
     */
    public static function processTokens($document)
    {
        $document = strtolower($document);
        $string = preg_replace('/[^a-z0-9 ]/', '', $document);
        $string = preg_replace('/[0-9]/', ' ', $document);
        $count = preg_match_all('/\w+/', $string, $matches);
        if ($count) {
            return $matches[0];
        }

        return [];
    }
}
