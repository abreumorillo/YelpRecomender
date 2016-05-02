<?php

namespace  YRS;

/**
 * @purpose     : This class implements the Naive Bayes algorithm for classifying reviews.
 * we are using three classes : Excellent, Good and Bad.
 * @course      : Knowledge Processing Technologies
 *
 * @author      : Team #6
 */
class NaiveBayesClassifier implements SaveToFileInterface
{

    /**
     * English stopwords list.
     *
     * @var array
     */
    protected $englishStopWords = array();

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
     * Parser instance.
     *
     * @var Parser
     */
    private $parser;
    /**
     * Constructor.
     *
     * @param Tokenizer
     **/
    public function __construct()
    {
        if(file_exists(SaveToFileInterface::TRAINED_CLASSIFIER_FILE_NAME)) {
            $this->getDataFromFile();
            return;
        }

        $this->englishStopWords = EnglishStopWords::get();
        $this->parser = new Parser();

    }

    public function saveDataToFile()
    {
        $dataToSave['totalSamples'] = $this->total_samples;
        $dataToSave['subjects'] = $this->subjects;
        $dataToSave['tokens'] = $this->tokens;
        $dataToSave['totalTokens'] = $this->total_tokens;

        ProjectStatistic::setClassifierTokenCount($this->total_tokens);
        $jsonData = json_encode($dataToSave);

        file_put_contents(SaveToFileInterface::TRAINED_CLASSIFIER_FILE_NAME, $jsonData);
    }


    public function getDataFromFile()
    {
        $dataFromFile = json_decode(file_get_contents(SaveToFileInterface::TRAINED_CLASSIFIER_FILE_NAME));

        $this->subjects = json_decode(json_encode($dataFromFile->subjects), true);
        $this->tokens = json_decode(json_encode($dataFromFile->tokens), true);
        $this->total_samples = $dataFromFile->totalSamples;
        $this->total_tokens = $dataFromFile->totalTokens;

    }

    /**
     * Train the classifier using the documents stored in the train folder.
     *
     * @return mix
     */
    public function trainClassifier()
    {
        $trainingDocuments = $this->parser->getTrainDocuments();
        foreach ($trainingDocuments as $class => $document) {
            $this->train($class, $document);
        }
        //Save data to file
        $this->saveDataToFile();
    }

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
    }

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
    }

    /**
     * Tokenize a string.
     *
     * Given a string, return an array of features to be
     * used for classification, it removes english stop words.
     *
     * @param string Input string
     *
     * @return array Features
     **/
    public function tokenize($document)
    {
        $words = Tokenizer::processTokens($document);
        foreach ($words as $token) {
            if (!in_array($token, $this->englishStopWords)) {
                $tokens[] = $token;
            }
        }

        return $tokens;
    } // end func: tokenize

    public function getClass($group)
    {
        $max = max($group);

        return array_keys($group, $max)[0];
    }
}
