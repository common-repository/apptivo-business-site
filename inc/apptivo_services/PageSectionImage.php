<?php

class AWP_PageSectionImage
{

  /**
   * 
   * @var string $imageName
   * @access public
   */
  public $imageName;

  /**
   * 
   * @var string $imageUrl
   * @access public
   */
  public $imageUrl;

  /**
   * 
   * @var string $mapCode
   * @access public
   */
  public $mapCode;

  /**
   * 
   * @var string $sequenceNumber
   * @access public
   */
  public $sequenceNumber;

  /**
   * 
   * @var string $keywords
   * @access public
   */
  public $keywords;

  /**
   * 
   * @var string $caption
   * @access public
   */
  public $caption;

  /**
   * 
   * @var string $imageId
   * @access public
   */
  public $imageId;

  /**
   * 
   * @var string $galleryType
   * @access public
   */
  public $galleryType;

  /**
   * 
   * @var string $videoCode
   * @access public
   */
  public $videoCode;

  /**
   * 
   * @var string $description
   * @access public
   */
  public $description;

  /**
   * 
   * @var dateTime $uploadedDate
   * @access public
   */
  public $uploadedDate;

  /**
   * 
   * @var string $sectionName
   * @access public
   */
  public $sectionName;

  /**
   * 
   * @var string $sectionId
   * @access public
   */
  public $sectionId;

  /**
   * 
   * @var string $targetUrl
   * @access public
   */
  public $targetUrl;

  /**
   * 
   * @var string $lastUpdatedBy
   * @access public
   */
  public $lastUpdatedBy;

  /**
   * 
   * @var string $documentKey
   * @access public
   */
  public $documentKey;

  /**
   * 
   * @var string $url
   * @access public
   */
  public $url;

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
   * @var int $id
   * @access public
   */
  public $id;

  /**
   * 
   * @param string $imageName
   * @param string $imageUrl
   * @param string $mapCode
   * @param string $sequenceNumber
   * @param string $keywords
   * @param string $caption
   * @param string $imageId
   * @param string $galleryType
   * @param string $videoCode
   * @param string $description
   * @param dateTime $uploadedDate
   * @param string $sectionName
   * @param string $sectionId
   * @param string $targetUrl
   * @param string $lastUpdatedBy
   * @param string $documentKey
   * @param string $url
   * @param string $documentName
   * @param string $documentNumber
   * @param string $fileSize
   * @param int $id
   * @access public
   */
  public function __construct($imageName, $imageUrl, $mapCode, $sequenceNumber, $keywords, $caption, $imageId, $galleryType, $videoCode, $description, $uploadedDate, $sectionName, $sectionId, $targetUrl, $lastUpdatedBy, $documentKey, $url, $documentName, $documentNumber, $fileSize, $id)
  {
    $this->imageName = $imageName;
    $this->imageUrl = $imageUrl;
    $this->mapCode = $mapCode;
    $this->sequenceNumber = $sequenceNumber;
    $this->keywords = $keywords;
    $this->caption = $caption;
    $this->imageId = $imageId;
    $this->galleryType = $galleryType;
    $this->videoCode = $videoCode;
    $this->description = $description;
    $this->uploadedDate = $uploadedDate;
    $this->sectionName = $sectionName;
    $this->sectionId = $sectionId;
    $this->targetUrl = $targetUrl;
    $this->lastUpdatedBy = $lastUpdatedBy;
    $this->documentKey = $documentKey;
    $this->url = $url;
    $this->documentName = $documentName;
    $this->documentNumber = $documentNumber;
    $this->fileSize = $fileSize;
    $this->id = $id;
  }

}