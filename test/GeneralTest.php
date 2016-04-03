<?php

require_once('../vendor/autoload.php');
use YRS\PorterStemmer;

class GeneralTest extends PHPUnit_Framework_TestCase
{
    
    public function testRegularExpressionForSplittingDocument()
    {
       //Arrange
       $splitRegex = "/[\s\"\.,:;&%~^+\(\)\$#!\?\/\\\-]+/";
       $document = "This is just a test";
       
       //Act
       $result = preg_split($splitRegex, $document);
       var_dump($resut);
       //Assert
       $this->assertTrue(is_array($result));
       $this->assertEquals(count($result), 5);
    }
    
    public function testPorterStemmer()
    {
        //Arrange
        $word = "ponies";
        //Act
        $stem = PorterStemmer::Stem($word);
        
        //Assert
        $this->assertEquals("poni", $stem);
    }

    // ...
}

