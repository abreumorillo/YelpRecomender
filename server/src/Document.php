<?php

namespace YRS;
/**
 *@purpose          : This class is used to represent a restaurant object
 *@course           : Knowledge Processing Technologies
 *@author           : Team #
 *@version          : 1.0
 *@see              : VectorSpaceModel
 */
class Document
{
    /**
     * Id of the document
     * @var integer
     */
    public $docId;

    /**
     * TF-IDF  of the document
     * @var double
     */
    public $termWeight;

    /**
     * Title of  the restaurant
     * @var string
     */
    public $title;

    /**
     * City where the restaurant is located
     * @var string
     */
    public $city;

    /**
     * State where the restaurant is located
     * @var string
     */
    public $state;

    /**
     * Class constructor
     * @param int $docId
     * @param double $termWeight
     * @param string $title
     * @param string $city
     * @param string $state
     */
    public function __construct($docId, $termWeight, $title, $city, $state)
    {
        $this->docId = $docId;
        $this->termWeight = $termWeight;
        $this->title = $title;
        $this->city = $city;
        $this->state = $state;
    }


}
