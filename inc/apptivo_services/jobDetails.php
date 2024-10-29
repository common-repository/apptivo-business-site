<?php

class jobDetails
{

  /**
   * 
   * @var dateTime $fillByDate
   * @access public
   */
  public $fillByDate;

  /**
   * 
   * @var int $firmId
   * @access public
   */
  public $firmId;

  /**
   * 
   * @var int $industryId
   * @access public
   */
  public $industryId;

  /**
   * 
   * @var string $industryName
   * @access public
   */
  public $industryName;

  /**
   * 
   * @var string $isFeatured
   * @access public
   */
  public $isFeatured;

  /**
   * 
   * @var string $jobDescription
   * @access public
   */
  public $jobDescription;

  /**
   * 
   * @var int $jobId
   * @access public
   */
  public $jobId;

  /**
   * 
   * @var string $jobNumber
   * @access public
   */
  public $jobNumber;

  /**
   * 
   * @var int $jobStatusId
   * @access public
   */
  public $jobStatusId;

  /**
   * 
   * @var string $jobStatusName
   * @access public
   */
  public $jobStatusName;

  /**
   * 
   * @var string $jobTitle
   * @access public
   */
  public $jobTitle;

  /**
   * 
   * @var int $jobTypeId
   * @access public
   */
  public $jobTypeId;

  /**
   * 
   * @var string $jobTypeName
   * @access public
   */
  public $jobTypeName;

  /**
   * 
   * @param dateTime $fillByDate
   * @param int $firmId
   * @param int $industryId
   * @param string $industryName
   * @param string $isFeatured
   * @param string $jobDescription
   * @param int $jobId
   * @param string $jobNumber
   * @param int $jobStatusId
   * @param string $jobStatusName
   * @param string $jobTitle
   * @param int $jobTypeId
   * @param string $jobTypeName
   * @access public
   */
  public function __construct($fillByDate, $firmId, $industryId, $industryName, $isFeatured, $jobDescription, $jobId, $jobNumber, $jobStatusId, $jobStatusName, $jobTitle, $jobTypeId, $jobTypeName)
  {
    $this->fillByDate = $fillByDate;
    $this->firmId = $firmId;
    $this->industryId = $industryId;
    $this->industryName = $industryName;
    $this->isFeatured = $isFeatured;
    $this->jobDescription = $jobDescription;
    $this->jobId = $jobId;
    $this->jobNumber = $jobNumber;
    $this->jobStatusId = $jobStatusId;
    $this->jobStatusName = $jobStatusName;
    $this->jobTitle = $jobTitle;
    $this->jobTypeId = $jobTypeId;
    $this->jobTypeName = $jobTypeName;
  }

}