<?php

class AWP_noteDetails
{

  /**
   * 
   * @var labelDetails $labels
   * @access public
   */
  public $labels;

  /**
   * 
   * @var string $noteId
   * @access public
   */
  public $noteId;

  /**
   * 
   * @var string $noteText
   * @access public
   */
  public $noteText;

  /**
   * 
   * @param labelDetails $labels
   * @param string $noteId
   * @param string $noteText
   * @access public
   */
  public function __construct($labels, $noteId, $noteText)
  {
    $this->labels = $labels;
    $this->noteId = $noteId;
    $this->noteText = $noteText;
  }

}