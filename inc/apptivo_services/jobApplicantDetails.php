<?php

class JobApplicantDetails
{

  /**
   * 
   * @var string $addressId
   * @access public
   */
  public $addressId;

  /**
   * 
   * @var string $addressLine1
   * @access public
   */
  public $addressLine1;

  /**
   * 
   * @var string $addressLine2
   * @access public
   */
  public $addressLine2;

  /**
   * 
   * @var int $applicantId
   * @access public
   */
  public $applicantId;

  /**
   * 
   * @var string $applicantNumber
   * @access public
   */
  public $applicantNumber;

  /**
   * 
   * @var string $city
   * @access public
   */
  public $city;

  /**
   * 
   * @var string $comments
   * @access public
   */
  public $comments;

  /**
   * 
   * @var string $country
   * @access public
   */
  public $country;

  /**
   * 
   * @var string $countyAndDistrict
   * @access public
   */
  public $countyAndDistrict;

  /**
   * 
   * @var string $emailId
   * @access public
   */
  public $emailId;

  /**
   * 
   * @var string $expectedDesignation
   * @access public
   */
  public $expectedDesignation;

  /**
   * 
   * @var string $expectedSalary
   * @access public
   */
  public $expectedSalary;

  /**
   * 
   * @var string $firstName
   * @access public
   */
  public $firstName;

  /**
   * 
   * @var string $industryId
   * @access public
   */
  public $industryId;

  /**
   * 
   * @var string $jobApplicantId
   * @access public
   */
  public $jobApplicantId;

  /**
   * 
   * @var string $jobId
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
   * @var string $lastName
   * @access public
   */
  public $lastName;

  /**
   * 
   * @var string $middleName
   * @access public
   */
  public $middleName;

  /**
   * 
   * @var noteDetails $noteDetails
   * @access public
   */
  public $noteDetails;

  /**
   * 
   * @var string $phoneNumber
   * @access public
   */
  public $phoneNumber;

  /**
   * 
   * @var string $postalCode
   * @access public
   */
  public $postalCode;

  /**
   * 
   * @var string $provinceAndState
   * @access public
   */
  public $provinceAndState;

  /**
   * 
   * @var string $resumeCoverLetter
   * @access public
   */
  public $resumeCoverLetter;

  /**
   * 
   * @var documentDetails $resumeDetails
   * @access public
   */
  public $resumeDetails;

  /**
   * 
   * @var string $resumeFileName
   * @access public
   */
  public $resumeFileName;

  /**
   * 
   * @var string $resumeId
   * @access public
   */
  public $resumeId;

  /**
   * 
   * @var string $skills
   * @access public
   */
  public $skills;

  /**
   * 
   * @var int $drDocumentId
   * @access public
   */
  public $drDocumentId;

  /**
   * 
   * @param string $addressId
   * @param string $addressLine1
   * @param string $addressLine2
   * @param int $applicantId
   * @param string $applicantNumber
   * @param string $city
   * @param string $comments
   * @param string $country
   * @param string $countyAndDistrict
   * @param string $emailId
   * @param string $expectedDesignation
   * @param string $expectedSalary
   * @param string $firstName
   * @param string $industryId
   * @param string $jobApplicantId
   * @param string $jobId
   * @param string $jobNumber
   * @param string $lastName
   * @param string $middleName
   * @param noteDetails $noteDetails
   * @param string $phoneNumber
   * @param string $postalCode
   * @param string $provinceAndState
   * @param string $resumeCoverLetter
   * @param documentDetails $resumeDetails
   * @param string $resumeFileName
   * @param string $resumeId
   * @param string $skills
   * @param int $drDocumentId
   * @access public
   */
  public function __construct($addressId, $addressLine1, $addressLine2, $applicantId, $applicantNumber, $city, $comments, $country, $countyAndDistrict, $emailId, $expectedDesignation, $expectedSalary, $firstName, $industryId, $jobApplicantId, $jobId, $jobNumber, $lastName, $middleName, $noteDetails, $phoneNumber, $postalCode, $provinceAndState, $resumeCoverLetter, $resumeDetails, $resumeFileName, $resumeId, $skills, $drDocumentId)
  {
    $this->addressId = $addressId;
    $this->addressLine1 = $addressLine1;
    $this->addressLine2 = $addressLine2;
    $this->applicantId = $applicantId;
    $this->applicantNumber = $applicantNumber;
    $this->city = $city;
    $this->comments = $comments;
    $this->country = $country;
    $this->countyAndDistrict = $countyAndDistrict;
    $this->emailId = $emailId;
    $this->expectedDesignation = $expectedDesignation;
    $this->expectedSalary = $expectedSalary;
    $this->firstName = $firstName;
    $this->industryId = $industryId;
    $this->jobApplicantId = $jobApplicantId;
    $this->jobId = $jobId;
    $this->jobNumber = $jobNumber;
    $this->lastName = $lastName;
    $this->middleName = $middleName;
    $this->noteDetails = $noteDetails;
    $this->phoneNumber = $phoneNumber;
    $this->postalCode = $postalCode;
    $this->provinceAndState = $provinceAndState;
    $this->resumeCoverLetter = $resumeCoverLetter;
    $this->resumeDetails = $resumeDetails;
    $this->resumeFileName = $resumeFileName;
    $this->resumeId = $resumeId;
    $this->skills = $skills;
    $this->drDocumentId = $drDocumentId;
  }

}