<?php 

namespace YRS;

use YRS\PorterStemmer;

class StemTokenizer {
    
    
    public static function getTokens($document)
    {
        $splitRegex = "/[\s\"\.,:;&%~^+\(\)\$#!\?\/\\\-]+/"; //Regex for splitting the document
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