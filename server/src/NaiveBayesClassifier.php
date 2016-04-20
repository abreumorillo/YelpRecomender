<?php

namespace  YRS;

class NaiveBayesClassifier
{

    protected  $englishStopWords = array();
    /**
     * @var array Classification subjects e.g. positive, negative
     **/
    protected $subjects = array();
    /**
     * @var array Tokens and their subject counts
     **/
    protected $tokens = array();
    /**
     * @var int Total number of rows trained with
     **/
    protected $total_samples = 0;
    /**
     * @var int Total number of tokens trained with
     **/
    protected $total_tokens = 0;

    /**
     * Constructor.
     *
     * @param Tokenizer
     **/
    public function __construct()
    {
        //Get the english stop words
        $this->englishStopWords = EnglishStopWords::get();
    } // end func: __construct
    /**
     * Train this Classifier with one or more rows.
     *
     * @param string Subject e.g. positive
     * @param string/array One or more rows to train from
     **/
    public function train($subject, $rows)
    {
        if (!isset($this->subjects[$subject])) {
            $this->subjects[$subject] = array(
                'count_samples' => 0,
                'count_tokens' => 0,
                'prior_value' => null,
            );
        }
        if (empty($rows)) {
            return $this;
        }
        if (!is_array($rows)) {
            $rows = array($rows);
        }
        foreach ($rows as $row) {
            ++$this->total_samples;
            ++$this->subjects[$subject]['count_samples'];
            $tokens = $this->tokenize($row);
            foreach ($tokens as $token) {
                if (!isset($this->tokens[$token][$subject])) {
                    $this->tokens[$token][$subject] = 0;
                }
                ++$this->tokens[$token][$subject];
                ++$this->subjects[$subject]['count_tokens'];
                ++$this->total_tokens;
            }
        }
    } // end func: train
    /**
     * Classify a given string.
     *
     * @param string Input string
     *
     * @return array Group probabilities
     **/
    public function classify($string)
    {
        if ($this->total_samples === 0) {
            return array();
        }
        $tokens = $this->tokenize($string);
        $total_score = 0;
        $scores = array();
        foreach ($this->subjects as $subject => $subject_data) {
            $subject_data['prior_value'] = log($subject_data['count_samples'] / $this->total_samples);
            $this->subjects[$subject] = $subject_data;
            $scores[$subject] = 0;
            foreach ($tokens as $token) {
                $count = isset($this->tokens[$token][$subject]) ? $this->tokens[$token][$subject] : 0;
                $scores[$subject] += log(($count + 1) / ($subject_data['count_tokens'] + $this->total_tokens));
            }
            $scores[$subject] = $subject_data['prior_value'] + $scores[$subject];
            $total_score += $scores[$subject];
        }
        $min = min($scores);
        $sum = 0;
        foreach ($scores as $subject => $score) {
            $scores[$subject] = exp($score - $min);
            $sum += $scores[$subject];
        }
        $total = 1 / $sum;
        foreach ($scores as $subject => $score) {
            $scores[$subject] = $score * $total;
        }
        arsort($scores);

        return $scores;
    } // end func: classify


    /**
     * Tokenize a string
     *
     * Given a string, return an array of features to be
     * used for classification, it removes english stop words.
     *
     * @param string Input string
     * @return array Features
     **/
    public function tokenize($document) {
        // $tokens = array();
        // $document = strtolower($document);
        // $document = preg_replace('/[^a-z0-9 ]/', '', $document);
        // $document = preg_replace('/[0-9]/', ' ', $document);
        // $count = preg_match_all('/\w+/', $document, $matches);
        // // return $count ? $matches[0] : array();
        $words = Tokenizer::processTokens($document);
        if($count) {
            foreach ($words as $token) {
                if(!in_array($token, $this->englishStopWords)){
                    $tokens[] = $token;
                }
            }
        }
        return $tokens;
    } // end func: tokenize
} // end class: Classifier
