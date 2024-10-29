<?php
class AWP_AccountUser
{

  /**
   * 
   * @var int $accountId
   * @access public
   */
  public $accountId;

  /**
   * 
   * @var string $accountName
   * @access public
   */
  public $accountName;

  /**
   * 
   * @var string $accountNumber
   * @access public
   */
  public $accountNumber;

  /**
   * 
   * @var string $accountType
   * @access public
   */
  public $accountType;

  /**
   * 
   * @var float $annualRevenue
   * @access public
   */
  public $annualRevenue;

  /**
   * 
   * @var ContactDetails $billingContactDetailsList
   * @access public
   */
  public $billingContactDetailsList;

  /**
   * 
   * @var string $description
   * @access public
   */
  public $description;

  /**
   * 
   * @var string $emailId
   * @access public
   */
  public $emailId;

  /**
   * 
   * @var int $firmId
   * @access public
   */
  public $firmId;

  /**
   * 
   * @var string $industry
   * @access public
   */
  public $industry;

  /**
   * 
   * @var int $marketId
   * @access public
   */
  public $marketId;

  /**
   * 
   * @var string $marketName
   * @access public
   */
  public $marketName;

  /**
   * 
   * @var string $password
   * @access public
   */
  public $password;

  /**
   * 
   * @var float $prepaidBalance
   * @access public
   */
  public $prepaidBalance;

  /**
   * 
   * @var string $previousCustomer
   * @access public
   */
  public $previousCustomer;

  /**
   * 
   * @var string $rating
   * @access public
   */
  public $rating;

  /**
   * 
   * @var int $segmentId
   * @access public
   */
  public $segmentId;

  /**
   * 
   * @var string $segmentName
   * @access public
   */
  public $segmentName;

  /**
   * 
   * @var ContactDetails $shippingContactDetailsList
   * @access public
   */
  public $shippingContactDetailsList;

  /**
   * 
   * @var string $tickerSymbol
   * @access public
   */
  public $tickerSymbol;

  /**
   * 
   * @var int $userId
   * @access public
   */
  public $userId;

  /**
   * 
   * @var methodResponse $methodResponse
   * @access public
   */
  public $methodResponse;

  /**
   * 
   * @var ContactDetails $primaryContactDetails
   * @access public
   */
  public $primaryContactDetails;

  /**
   * 
   * @var ContactDetails $allContactDetailsList
   * @access public
   */
  public $allContactDetailsList;

  /**
   * 
   * @var string $loyaltyMembershipNumber
   * @access public
   */
  public $loyaltyMembershipNumber;

  /**
   * 
   * @var string $accountNumberValidated
   * @access public
   */
  public $accountNumberValidated;

  /**
   * 
   * @var dateTime $creationDate
   * @access public
   */
  public $creationDate;

  /**
   * 
   * @var int $customerCategoryId
   * @access public
   */
  public $customerCategoryId;

  /**
   * 
   * @var string $customerCategoryName
   * @access public
   */
  public $customerCategoryName;

  /**
   * 
   * @var string $createLogin
   * @access public
   */
  public $createLogin;

  /**
   * 
   * @var string $businessPhoneNumber
   * @access public
   */
  public $businessPhoneNumber;

  /**
   * 
   * @var string $createDistributor
   * @access public
   */
  public $createDistributor;

  /**
   * 
   * @var string $isDistributorLogin
   * @access public
   */
  public $isDistributorLogin;

  /**
   * 
   * @var string $website
   * @access public
   */
  public $website;

  /**
   * 
   * @param int $accountId
   * @param string $accountName
   * @param string $accountNumber
   * @param string $accountType
   * @param float $annualRevenue
   * @param ContactDetails $billingContactDetailsList
   * @param string $description
   * @param string $emailId
   * @param int $firmId
   * @param string $industry
   * @param int $marketId
   * @param string $marketName
   * @param string $password
   * @param float $prepaidBalance
   * @param string $previousCustomer
   * @param string $rating
   * @param int $segmentId
   * @param string $segmentName
   * @param ContactDetails $shippingContactDetailsList
   * @param string $tickerSymbol
   * @param int $userId
   * @param methodResponse $methodResponse
   * @param ContactDetails $primaryContactDetails
   * @param ContactDetails $allContactDetailsList
   * @param string $loyaltyMembershipNumber
   * @param string $accountNumberValidated
   * @param dateTime $creationDate
   * @param int $customerCategoryId
   * @param string $customerCategoryName
   * @param string $createLogin
   * @param string $businessPhoneNumber
   * @param string $createDistributor
   * @param string $isDistributorLogin
   * @param string $website
   * @access public
   */
  public function __construct($accountId, $accountName, $accountNumber, $accountType, $annualRevenue, $billingContactDetailsList, $description, $emailId, $firmId, $industry, $marketId, $marketName, $password, $prepaidBalance, $previousCustomer, $rating, $segmentId, $segmentName, $shippingContactDetailsList, $tickerSymbol, $userId, $methodResponse, $primaryContactDetails, $allContactDetailsList, $loyaltyMembershipNumber, $accountNumberValidated, $creationDate, $customerCategoryId, $customerCategoryName, $createLogin, $businessPhoneNumber, $createDistributor, $isDistributorLogin, $website)
  {
    $this->accountId = $accountId;
    $this->accountName = $accountName;
    $this->accountNumber = $accountNumber;
    $this->accountType = $accountType;
    $this->annualRevenue = $annualRevenue;
    $this->billingContactDetailsList = $billingContactDetailsList;
    $this->description = $description;
    $this->emailId = $emailId;
    $this->firmId = $firmId;
    $this->industry = $industry;
    $this->marketId = $marketId;
    $this->marketName = $marketName;
    $this->password = $password;
    $this->prepaidBalance = $prepaidBalance;
    $this->previousCustomer = $previousCustomer;
    $this->rating = $rating;
    $this->segmentId = $segmentId;
    $this->segmentName = $segmentName;
    $this->shippingContactDetailsList = $shippingContactDetailsList;
    $this->tickerSymbol = $tickerSymbol;
    $this->userId = $userId;
    $this->methodResponse = $methodResponse;
    $this->primaryContactDetails = $primaryContactDetails;
    $this->allContactDetailsList = $allContactDetailsList;
    $this->loyaltyMembershipNumber = $loyaltyMembershipNumber;
    $this->accountNumberValidated = $accountNumberValidated;
    $this->creationDate = $creationDate;
    $this->customerCategoryId = $customerCategoryId;
    $this->customerCategoryName = $customerCategoryName;
    $this->createLogin = $createLogin;
    $this->businessPhoneNumber = $businessPhoneNumber;
    $this->createDistributor = $createDistributor;
    $this->isDistributorLogin = $isDistributorLogin;
    $this->website = $website;
  }

}