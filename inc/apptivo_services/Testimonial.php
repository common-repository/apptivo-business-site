<?php

class AWP_MktTestimonial
{


  /**
   *
   * @var AccountUser $account
   * @access public
   */
  public $account;

  /**
   *
   * @var int $accountId
   * @access public
   */
  public $accountId;

  /**
   *
   * @var string $company
   * @access public
   */
  public $company;

  /**
   *
   * @var ContactDetails $contact
   * @access public
   */
  public $contact;

  /**
   *
   * @var int $contactId
   * @access public
   */
  public $contactId;

  /**
   *
   * @var dateTime $creationDate
   * @access public
   */
  public $creationDate;

  /**
   *
   * @var string $email
   * @access public
   */
  public $email;

  /**
   *
   * @var string $firmId
   * @access public
   */
  public $firmId;

  /**
   *
   * @var PageSectionImage $images
   * @access public
   */
  public $images;

  /**
   *
   * @var string $jobTitle
   * @access public
   */
  public $jobTitle;

  /**
   *
   * @var string $name
   * @access public
   */
  public $name;

  /**
   *
   * @var string $returnStatus
   * @access public
   */
  public $returnStatus;

  /**
   *
   * @var int $sequenceNumber
   * @access public
   */
  public $sequenceNumber;

  /**
   *
   * @var string $siteTestimonialId
   * @access public
   */
  public $siteTestimonialId;

  /**
   *
   * @var string $testimonial
   * @access public
   */
  public $testimonial;

  /**
   *
   * @var string $testimonialImageUrl
   * @access public
   */
  public $testimonialImageUrl;

  /**
   *
   * @var string $testimonialStatus
   * @access public
   */
  public $testimonialStatus;

  /**
   *
   * @var string $website
   * @access public
   */
  public $website;

  /**
   *
   * @param AccountUser $account
   * @param int $accountId
   * @param string $company
   * @param ContactDetails $contact
   * @param int $contactId
   * @param dateTime $creationDate
   * @param string $email
   * @param string $firmId
   * @param PageSectionImage $images
   * @param string $jobTitle
   * @param string $name
   * @param string $returnStatus
   * @param int $sequenceNumber
   * @param string $siteTestimonialId
   * @param string $testimonial
   * @param string $testimonialImageUrl
   * @param string $testimonialStatus
   * @param string $website
   * @access public
   */
  public function __construct($account, $accountId, $company, $contact, $contactId, $creationDate, $email, $firmId, $images, $jobTitle, $name, $returnStatus, $sequenceNumber, $siteTestimonialId, $testimonial, $testimonialImageUrl, $testimonialStatus, $website)
  {
    $this->account = $account;
    $this->accountId = $accountId;
    $this->company = $company;
    $this->contact = $contact;
    $this->contactId = $contactId;
    $this->creationDate = $creationDate;
    $this->email = $email;
    $this->firmId = $firmId;
    $this->images = $images;
    $this->jobTitle = $jobTitle;
    $this->name = $name;
    $this->returnStatus = $returnStatus;
    $this->sequenceNumber = $sequenceNumber;
    $this->siteTestimonialId = $siteTestimonialId;
    $this->testimonial = $testimonial;
    $this->testimonialImageUrl = $testimonialImageUrl;
    $this->testimonialStatus = $testimonialStatus;
    $this->website = $website;
  }
}