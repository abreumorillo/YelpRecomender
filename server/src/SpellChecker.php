<?php

namespace  YRS;

/**
 * @purpose      : Class used to handle spelling correction using Levenshtein edit distance.
 * @courser      : Knowledge Processing Technologies
 * @author       : Group # 6
 */
class SpellChecker
{
    private  $vocabulary = array();

    public function __construct()
    {
        $this->vocabulary = Tokenizer::getRawToken();
    }

    /**
     * Check for spelling correction
     * @param  string $token word to be checked for spelling correction
     * @return array
     */
    public function check($token)
    {
        $result = array();
        $suggestedCount = 0;

        foreach ($this->vocabulary as $word) {
            // calculate the distance between the token, and the current word (from dictionary)
            $distance = levenshtein($token, strtolower(trim($word)));
            // var_dump($distance);
            // If distance is 0 to any of the word it means we have spelled correctly
            if ($distance === 0) {
                break;
            }
            //We only consider pretty similar words and suggest a maximum of 3 words
            if ($distance < 2 && $suggestedCount < 3) {
                $result[] = $word;
                $suggestedCount++;
            }
        }
        return  $result;
    }
}
