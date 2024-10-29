<?php

class AWP_ContactDetails
{

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
   * @var int $addressId
   * @access public
   */
  public $addressId;

  /**
   * 
   * @var int $associationId
   * @access public
   */
  public $associationId;

  /**
   * 
   * @var string $city
   * @access public
   */
  public $city;

  /**
   * 
   * @var string $companyName
   * @access public
   */
  public $companyName;

  /**
   * 
   * @var int $contactId
   * @access public
   */
  public $contactId;

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
   * @var dateTime $dateOfBirth
   * @access public
   */
  public $dateOfBirth;

  /**
   * 
   * @var string $emailId
   * @access public
   */
  public $emailId;

  /**
   * 
   * @var string $fax
   * @access public
   */
  public $fax;

  /**
   * 
   * @var string $firstName
   * @access public
   */
  public $firstName;

  /**
   * 
   * @var string $homePhoneNo
   * @access public
   */
  public $homePhoneNo;

  /**
   * 
   * @var string $jobTitle
   * @access public
   */
  public $jobTitle;

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
   * @var string $mobileNo
   * @access public
   */
  public $mobileNo;

  /**
   * 
   * @var string $postalCode
   * @access public
   */
  public $postalCode;

  /**
   * 
   * @var string $primaryContact
   * @access public
   */
  public $primaryContact;

  /**
   * 
   * @var string $provinceAndState
   * @access public
   */
  public $provinceAndState;

  /**
   * 
   * @var string $title
   * @access public
   */
  public $title;

  /**
   * 
   * @var string $type
   * @access public
   */
  public $type;

  /**
   * 
   * @var PaymentDetails $paymentDetails
   * @access public
   */
  public $paymentDetails;

  /**
   * 
   * @var noteDetails $noteDetails
   * @access public
   */
  public $noteDetails;

  /**
   * 
   * @var string $sameAsBillingAddress
   * @access public
   */
  public $sameAsBillingAddress;

  /**
   * 
   * @var string $dateOfBirthStr
   * @access public
   */
  public $dateOfBirthStr;

  /**
   * 
   * @var string $dateFormat
   * @access public
   */
  public $dateFormat;

  /**
   * 
   * @var string $twitterId
   * @access public
   */
  public $twitterId;

  /**
   * 
   * @param string $address1
   * @param string $address2
   * @param int $addressId
   * @param int $associationId
   * @param string $city
   * @param string $companyName
   * @param int $contactId
   * @param string $country
   * @param string $countyAndDistrict
   * @param dateTime $dateOfBirth
   * @param string $emailId
   * @param string $fax
   * @param string $firstName
   * @param string $homePhoneNo
   * @param string $jobTitle
   * @param string $lastName
   * @param string $middleName
   * @param string $mobileNo
   * @param string $postalCode
   * @param string $primaryContact
   * @param string $provinceAndState
   * @param string $title
   * @param string $type
   * @param PaymentDetails $paymentDetails
   * @param noteDetails $noteDetails
   * @param string $sameAsBillingAddress
   * @param string $dateOfBirthStr
   * @param string $dateFormat
   * @param string $twitterId
   * @access public
   */
  public function __construct($address1, $address2, $addressId, $associationId, $city, $companyName, $contactId, $country, $countyAndDistrict, $dateOfBirth, $emailId, $fax, $firstName, $homePhoneNo, $jobTitle, $lastName, $middleName, $mobileNo, $postalCode, $primaryContact, $provinceAndState, $title, $type, $paymentDetails, $noteDetails, $sameAsBillingAddress, $dateOfBirthStr, $dateFormat, $twitterId)
  {
    $this->address1 = $address1;
    $this->address2 = $address2;
    $this->addressId = $addressId;
    $this->associationId = $associationId;
    $this->city = $city;
    $this->companyName = $companyName;
    $this->contactId = $contactId;
    $this->country = $country;
    $this->countyAndDistrict = $countyAndDistrict;
    $this->dateOfBirth = $dateOfBirth;
    $this->emailId = $emailId;
    $this->fax = $fax;
    $this->firstName = $firstName;
    $this->homePhoneNo = $homePhoneNo;
    $this->jobTitle = $jobTitle;
    $this->lastName = $lastName;
    $this->middleName = $middleName;
    $this->mobileNo = $mobileNo;
    $this->postalCode = $postalCode;
    $this->primaryContact = $primaryContact;
    $this->provinceAndState = $provinceAndState;
    $this->title = $title;
    $this->type = $type;
    $this->paymentDetails = $paymentDetails;
    $this->noteDetails = $noteDetails;
    $this->sameAsBillingAddress = $sameAsBillingAddress;
    $this->dateOfBirthStr = $dateOfBirthStr;
    $this->dateFormat = $dateFormat;
    $this->twitterId = $twitterId;
  }

}