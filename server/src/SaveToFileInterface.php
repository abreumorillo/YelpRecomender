<?php

namespace  YRS;

/**
 *
 */
interface  SaveToFileInterface
{
    const TRAINED_CLASSIFIER_FILE_NAME = 'optimization/classifier_info.json';
    const SPELLING_CORRECTION_FILE_NAME = 'optimization/vocabulary_info.json';
    const INDEX_FILE_NAME = 'optimization/index_info.json';
    const STATISTIC_FILE_NAME = 'optimization/statistics_info.json';

    public function saveDataToFile();
    public function getDataFromFile();
}
