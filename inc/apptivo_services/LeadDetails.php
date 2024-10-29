<?php

class AWP_LeadDetails
{
  /**
   *
   * @var string $firmId
   * @access public
   */
  public $firmId;

  /**
   *
   * @var string $firstName
   * @access public
   */
  public $firstName;

  /**
   *
   * @var string $lastName
   * @access public
   */
  public $lastName;

  /**
   *
   * @var string $emailId
   * @access public
   */
  public $emailId;

  /**
   *
   * @var string $jobTitle
   * @access public
   */
  public $jobTitle;

  /**
   *
   * @var string $company
   * @access public
   */
  public $company;

  /**
   *
   * @var string $address1
   * @access public
   */
  public $address1;

  /**
   *
   * @var string $address2
   * @access public
   */
  public $address2;

  /**
   *
   * @var string $city
   * @access public
   */
  public $city;

  /**
   *
   * @var string $state
   * @access public
   */
  public $state;

  /**
   *
   * @var string $zipCode
   * @access public
   */
  public $zipCode;

  /**
   *
   * @var string $bestWayToContact
   * @access public
   */
  public $bestWayToContact;

  /**
   *
   * @var string $country
   * @access public
   */
  public $country;

  /**
   *
   * @var string $leadSource
   * @access public
   */
  public $leadSource;

  /**
   *
   * @var string $phoneNumber
   * @access public
   */
  public $phoneNumber;

  /**
   *
   * @var string $comments
   * @access public
   */
  public $comments;

  /**
   *
   * @var noteDetails $noteDetails
   * @access public
   */
  public $noteDetails;

  /**
   *
   * @var int $targetListId
   * @access public
   */
  public $targetListId;

  /**
   *
   * @param string $firmId
   * @param string $firstName
   * @param string $lastName
   * @param string $emailId
   * @param string $jobTitle
   * @param string $company
   * @param string $address1
   * @param string $address2
   * @param string $city
   * @param string $state
   * @param string $zipCode
   * @param string $bestWayToContact
   * @param string $country
   * @param string $leadSource
   * @param string $phoneNumber
   * @param string $comments
   * @param noteDetails $noteDetails
   * @param int $targetListId
   * @access public
   */
  public function __construct($firmId, $firstName, $lastName, $emailId, $jobTitle, $company, $address1, $address2, $city, $state, $zipCode, $bestWayToContact, $country, $leadSource, $phoneNumber, $comments, $noteDetails, $targetListId)
  {
    $this->firmId = $firmId;
    $this->firstName = $firstName;
    $this->lastName = $lastName;
    $this->emailId = $emailId;
    $this->jobTitle = $jobTitle;
    $this->company = $company;
    $this->address1 = $address1;
    $this->address2 = $address2;
    $this->city = $city;
    $this->state = $state;
    $this->zipCode = $zipCode;
    $this->bestWayToContact = $bestWayToContact;
    $this->country = $country;
    $this->leadSource = $leadSource;
    $this->phoneNumber = $phoneNumber;
    $this->comments = $comments;
    $this->noteDetails = $noteDetails;
    $this->targetListId = $targetListId;
  }

}