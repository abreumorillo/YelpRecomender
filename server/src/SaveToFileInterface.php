<?php

namespace  YRS;

/**
 *
 */
interface  SaveToFileInterface
{
    const TRAINED_CLASSIFIER_FILE_NAME = 'classifier_info.json';
    const SPELLING_CORRECTION_FILE_NAME = 'vocabulary_info.json';
    const INDEX_FILE_NAME = 'index_info.json';

    public function saveDataToFile();
    public function getDataFromFile();
}
