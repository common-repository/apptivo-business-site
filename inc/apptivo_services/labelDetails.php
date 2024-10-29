<?php

class AWP_labelDetails
{

  /**
   * 
   * @var string $labelId
   * @access public
   */
  public $labelId;

  /**
   * 
   * @var string $labelName
   * @access public
   */
  public $labelName;

  /**
   * 
   * @param string $labelId
   * @param string $labelName
   * @access public
   */
  public function __construct($labelId, $labelName)
  {
    $this->labelId = $labelId;
    $this->labelName = $labelName;
  }

}