<?php

class AWP_MarketingEvent
{

 /**
   *
   * @var string $eventName
   * @access public
   */
  public $eventName;

  /**
   *
   * @var string $description
   * @access public
   */
  public $description;

  /**
   *
   * @var dateTime $startDate
   * @access public
   */
  public $startDate;

  /**
   *
   * @var dateTime $endDate
   * @access public
   */
  public $endDate;

  /**
   *
   * @var string $displayFirstName
   * @access public
   */
  public $displayFirstName;

  /**
   *
   * @var string $displayLastName
   * @access public
   */
  public $displayLastName;

  /**
   *
   * @var string $displayAddress
   * @access public
   */
  public $displayAddress;

  /**
   *
   * @var string $displayEmailId
   * @access public
   */
  public $displayEmailId;

  /**
   *
   * @var string $displayPhoneNumber
   * @access public
   */
  public $displayPhoneNumber;

  /**
   *
   * @var string $sendRegistrationEmail
   * @access public
   */
  public $sendRegistrationEmail;

  /**
   *
   * @var string $registrantFirstName
   * @access public
   */
  public $registrantFirstName;

  /**
   *
   * @var string $registrantLastName
   * @access public
   */
  public $registrantLastName;

  /**
   *
   * @var string $registrantEmailId
   * @access public
   */
  public $registrantEmailId;

  /**
   *
   * @var string $registrantPhoneNumber
   * @access public
   */
  public $registrantPhoneNumber;

  /**
   *
   * @var string $registrantAddressLine1
   * @access public
   */
  public $registrantAddressLine1;

  /**
   *
   * @var string $registrantAddressLine2
   * @access public
   */
  public $registrantAddressLine2;

  /**
   *
   * @var string $registrantCity
   * @access public
   */
  public $registrantCity;

  /**
   *
   * @var string $registrantStateCode
   * @access public
   */
  public $registrantStateCode;

  /**
   *
   * @var string $registrantStateName
   * @access public
   */
  public $registrantStateName;

  /**
   *
   * @var string $registrantPinCode
   * @access public
   */
  public $registrantPinCode;

  /**
   *
   * @var string $registrantCountryCode
   * @access public
   */
  public $registrantCountryCode;

  /**
   *
   * @var string $registrantCountryName
   * @access public
   */
  public $registrantCountryName;

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
   * @var string $marketingEventId
   * @access public
   */
  public $marketingEventId;

  /**
   *
   * @var dateTime $creationDate
   * @access public
   */
  public $creationDate;

  /**
   *
   * @var string $eventImages
   * @access public
   */
  public $eventImages;

  /**
   *
   * @param string $eventName
   * @param string $description
   * @param dateTime $startDate
   * @param dateTime $endDate
   * @param string $displayFirstName
   * @param string $displayLastName
   * @param string $displayAddress
   * @param string $displayEmailId
   * @param string $displayPhoneNumber
   * @param string $sendRegistrationEmail
   * @param string $registrantFirstName
   * @param string $registrantLastName
   * @param string $registrantEmailId
   * @param string $registrantPhoneNumber
   * @param string $registrantAddressLine1
   * @param string $registrantAddressLine2
   * @param string $registrantCity
   * @param string $registrantStateCode
   * @param string $registrantStateName
   * @param string $registrantPinCode
   * @param string $registrantCountryCode
   * @param string $registrantCountryName
   * @param PageSectionImage $pageSectionImages
   * @param string $link
   * @param string $publishedAt
   * @param string $publishedBy
   * @param int $sequenceNumber
   * @param string $marketingEventId
   * @param dateTime $creationDate
   * @param string $eventImages
   * @access public
   */
  public function __construct($eventName, $description, $startDate, $endDate, $displayFirstName, $displayLastName, $displayAddress, $displayEmailId, $displayPhoneNumber, $sendRegistrationEmail, $registrantFirstName, $registrantLastName, $registrantEmailId, $registrantPhoneNumber, $registrantAddressLine1, $registrantAddressLine2, $registrantCity, $registrantStateCode, $registrantStateName, $registrantPinCode, $registrantCountryCode, $registrantCountryName, $pageSectionImages, $link, $publishedAt, $publishedBy, $sequenceNumber, $marketingEventId, $creationDate, $eventImages)
  {
    $this->eventName = $eventName;
    $this->description = $description;
    $this->startDate = $startDate;
    $this->endDate = $endDate;
    $this->displayFirstName = $displayFirstName;
    $this->displayLastName = $displayLastName;
    $this->displayAddress = $displayAddress;
    $this->displayEmailId = $displayEmailId;
    $this->displayPhoneNumber = $displayPhoneNumber;
    $this->sendRegistrationEmail = $sendRegistrationEmail;
    $this->registrantFirstName = $registrantFirstName;
    $this->registrantLastName = $registrantLastName;
    $this->registrantEmailId = $registrantEmailId;
    $this->registrantPhoneNumber = $registrantPhoneNumber;
    $this->registrantAddressLine1 = $registrantAddressLine1;
    $this->registrantAddressLine2 = $registrantAddressLine2;
    $this->registrantCity = $registrantCity;
    $this->registrantStateCode = $registrantStateCode;
    $this->registrantStateName = $registrantStateName;
    $this->registrantPinCode = $registrantPinCode;
    $this->registrantCountryCode = $registrantCountryCode;
    $this->registrantCountryName = $registrantCountryName;
    $this->pageSectionImages = $pageSectionImages;
    $this->link = $link;
    $this->publishedAt = $publishedAt;
    $this->publishedBy = $publishedBy;
    $this->sequenceNumber = $sequenceNumber;
    $this->marketingEventId = $marketingEventId;
    $this->creationDate = $creationDate;
    $this->eventImages = $eventImages;
  }

}