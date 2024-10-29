<?php

class AWP_appParam
{

  /**
   * 
   * @var string $name
   * @access public
   */
  public $name;

  /**
   * 
   * @var string $value
   * @access public
   */
  public $value;

  /**
   * 
   * @param string $name
   * @param string $value
   * @access public
   */
  public function __construct($name, $value)
  {
    $this->name = $name;
    $this->value = $value;
  }

}
