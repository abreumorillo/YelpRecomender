<?php

namespace  YRS;

/**
 * @purpose         : class for getting global project statistics.
 * @coruse          : Knowledge Processing Technologies.
 *
 * @author          : Team #6
 */
class ProjectStatistic
{
    const STATISTIC_FILE_NAME = 'optimization/statistics_info.json';

    private static $tokenCount;
    private static $vocabularySize;
    private static $trainingDocumentsCount = 3000; //1000 per class
    private static $englishStopWordsCount;
    private static $testingDocumentsCount;
    private static $correctlyClassified;
    private static $classifierAccurary;
    private static $classifierTokenCount;
    private static $classifierClasses = ['Excellent', 'Good', 'Bad'];

    public static function getStatistics()
    {
        $statistics = self::getDataFromFile();
        return $statistics;
    }

    public static function initialize()
    {
        if(!file_exists(self::STATISTIC_FILE_NAME)) {
            self::saveDataToFile();
        }
    }
    //STATISTIC_FILE_NAME

    public static function saveDataToFile()
    {
        $dataToSave['tokenCount'] = self::$tokenCount;
        $dataToSave['vocabularySize'] = self::$vocabularySize;
        $dataToSave['trainingDocumentsCount'] = self::$trainingDocumentsCount;
        $dataToSave['englishStopWordsCount'] = self::$englishStopWordsCount;
        $dataToSave['testingDocumentsCount'] = self::$testingDocumentsCount;
        $dataToSave['correctlyClassified'] = self::$correctlyClassified;
        $dataToSave['classifierAccurary'] = self::$classifierAccurary;
        $dataToSave['classifierTokenCount'] = self::$classifierTokenCount;

        $jsonStatistic = json_encode($dataToSave);

        file_put_contents(self::STATISTIC_FILE_NAME, $jsonStatistic);
    }

    public static function getDataFromFile()
    {
        $dataFromFile =  json_decode(file_get_contents(self::STATISTIC_FILE_NAME));

        $result['tokenCount'] = $dataFromFile->tokenCount;
        $result['vocabularySize'] = $dataFromFile->vocabularySize;
        $result['trainingDocumentsCount'] = $dataFromFile->trainingDocumentsCount;
        $result['englishStopWordsCount'] = $dataFromFile->englishStopWordsCount;
        $result['testingDocumentsCount'] = $dataFromFile->testingDocumentsCount;
        $result['correctlyClassified'] = $dataFromFile->correctlyClassified;
        $result['classifierAccurary'] = $dataFromFile->classifierAccurary;
        $result['classifierTokenCount'] = $dataFromFile->classifierTokenCount;
        $result['classifierClasses'] = self::$classifierClasses;

        return $result;
    }

    public static function setSpellingVocabularySize($count)
    {
        self::$vocabularySize = $count;
    }

    public static function setEnglishStopWordsCount($count)
    {
        self::$englishStopWordsCount = $count;
    }

    public static function setTokenCount($count)
    {
        self::$tokenCount = $count;
    }

    public static function setClassifierTokenCount($count)
    {
        self::$classifierTokenCount = $count;
    }

}
