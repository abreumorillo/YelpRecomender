<?php

namespace YRS;
/**
 * @purpose   : This class is responsible of the parsing process. For the purpose of this project are documents are represente using JSON files
 * @course      : Knowledge Processing Technologies
 * @author      : Team #
 * @caveat      : This parser only works with JSON formatted files.
 */
class Parser
{
    /**
     * Root directory where documents are stored.
     * @var string
     */
    private static $documentFolder = 'data/';

    /**
     * Array for storing parsed documents.
     * @var array
     */
    private $documents =[];

    /**
     * WHen the class is instantiated, the parse function is executed.
     */
    public function __construct()
    {
        $this->parse();
    }

    /**
     * Parses the JSON files from the data directory
     * @return array object document
     */
    private function parse()
    {
        foreach (glob('data/*.json') as $key => $file) {
            $document = json_decode(file_get_contents($file));
            preg_match('/\/(.*?)\./', $file, $match);
            $document->fileName = $match[1];
            $this->documents[] = $document;
        }
    }

    /**
     * Return the parsed documents
     * @return [type] [description]
     */
    public  function getDocuments()
    {
        return $this->documents;
    }
}
