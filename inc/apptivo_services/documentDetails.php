<?php

class documentDetails
{

  /**
   * 
   * @var string $caption
   * @access public
   */
  public $caption;

  /**
   * 
   * @var string $description
   * @access public
   */
  public $description;

  /**
   * 
   * @var string $documentKey
   * @access public
   */
  public $documentKey;

  /**
   * 
   * @var string $documentName
   * @access public
   */
  public $documentName;

  /**
   * 
   * @var string $documentNumber
   * @access public
   */
  public $documentNumber;

  /**
   * 
   * @var string $fileSize
   * @access public
   */
  public $fileSize;

  /**
   * 
   * @var string $keyWords
   * @access public
   */
  public $keyWords;

  /**
   * 
   * @var string $sequenceNumber
   * @access public
   */
  public $sequenceNumber;

  /**
   * 
   * @param string $caption
   * @param string $description
   * @param string $documentKey
   * @param string $documentName
   * @param string $documentNumber
   * @param string $fileSize
   * @param string $keyWords
   * @param string $sequenceNumber
   * @access public
   */
  public function __construct($caption, $description, $documentKey, $documentName, $documentNumber, $fileSize, $keyWords, $sequenceNumber)
  {
    $this->caption = $caption;
    $this->description = $description;
    $this->documentKey = $documentKey;
    $this->documentName = $documentName;
    $this->documentNumber = $documentNumber;
    $this->fileSize = $fileSize;
    $this->keyWords = $keyWords;
    $this->sequenceNumber = $sequenceNumber;
  }

}