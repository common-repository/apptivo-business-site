<?php
class AttributeNewCustom {

	public $rightData;
	
	public function setRightData($rightData){
		$this->rightData = $rightData;	
	}

	public function getRightData(){
		return $this->rightData;	
	}

	/*setter */
	private $attributeId;
	public function setAttributeId($attrId){
		$this->attributeId = $attrId;
	}

	public function getAttributeId(){
		return $this->attributeId;
	}

	private $type;
	public function setAttributeType($type){
		$this->type = $type;
	}

	public function getAttributeType(){
		return $this->type;
	}

	private $modifiedLabel;
	public function setAttributeLabel($label){
		$this->modifiedLabel = $label;
	}

	public function getAttributeLabel(){
		return $this->modifiedLabel;
	}

	private $isEnabled;
	public function setisEnabled($flag){
		$this->isEnabled = $flag;	
	}

	public function getisEnabled(){
		return $this->isEnabled;
	}

}
?>
