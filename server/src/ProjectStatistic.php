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
    private static $tokenCount;
    private static $vocabularySize;
    private static $trainingDocumentsCount = 3000; //1000 per class
    private static $englishStopWordsCount;
    private static $testingDocumentsCount;
    private static $correctlyClassified;
    private static $classifierAccurary;

    public static function getStatistics()
    {
        $statistics = [];

        $statistics['tokenCount'] = self::$tokenCount;
        $statistics['vocabularySize'] = self::$vocabularySize;
        $statistics['trainingDocumentsCount'] = self::$trainingDocumentsCount;
        $statistics['englishStopWordsCount'] = self::$englishStopWordsCount;
        $statistics['testingDocumentsCount'] = self::$testingDocumentsCount;
        $statistics['correctlyClassified'] = self::$correctlyClassified;
        $statistics['classifierAccurary'] = self::$classifierAccurary;

        return $statistics;
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
}
