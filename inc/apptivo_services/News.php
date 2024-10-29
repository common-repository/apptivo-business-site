<?php

class AWP_MktNews
{

  /**
   * 
   * @var string $newsId
   * @access public
   */
  public $newsId;

  /**
   * 
   * @var string $newsHeadLine
   * @access public
   */
  public $newsHeadLine;

  /**
   * 
   * @var string $description
   * @access public
   */
  public $description;

  /**
   * 
   * @var string $isFeatured
   * @access public
   */
  public $isFeatured;

  /**
   * 
   * @var dateTime $startDate
   * @access public
   */
  public $startDate;

  /**
   * 
   * @var PageSectionImage $pageSectionImages
   * @access public
   */
  public $pageSectionImages;

  /**
   * 
   * @var string $link
   * @access public
   */
  public $link;

  /**
   * 
   * @var string $publishedAt
   * @access public
   */
  public $publishedAt;

  /**
   * 
   * @var string $publishedBy
   * @access public
   */
  public $publishedBy;

  /**
   * 
   * @var int $sequenceNumber
   * @access public
   */
  public $sequenceNumber;

  /**
   * 
   * @var dateTime $endDate
   * @access public
   */
  public $endDate;

  /**
   * 
   * @var dateTime $creationDate
   * @access public
   */
  public $creationDate;
   /**
   *
   * @var string $newsImages
   * @access public
   */
  public $newsImages;
  /**
   * 
   * @param string $newsId
   * @param string $newsHeadLine
   * @param string $description
   * @param string $isFeatured
   * @param dateTime $startDate
   * @param PageSectionImage $pageSectionImages
   * @param string $link
   * @param string $publishedAt
   * @param string $publishedBy
   * @param int $sequenceNumber
   * @param dateTime $endDate
   * @param dateTime $creationDate
   * @access public
   */
  public function __construct($newsId, $newsHeadLine, $description, $isFeatured, $startDate, $pageSectionImages, $link, $publishedAt, $publishedBy, $sequenceNumber, $endDate, $creationDate,$newsImages)
  {
    $this->newsId = $newsId;
    $this->newsHeadLine = $newsHeadLine;
    $this->description = $description;
    $this->isFeatured = $isFeatured;
    $this->startDate = $startDate;
    $this->pageSectionImages = $pageSectionImages;
    $this->link = $link;
    $this->publishedAt = $publishedAt;
    $this->publishedBy = $publishedBy;
    $this->sequenceNumber = $sequenceNumber;
    $this->endDate = $endDate;
    $this->creationDate = $creationDate;
    $this->newsImages = $newsImages;
  }

}