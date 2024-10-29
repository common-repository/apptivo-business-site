<?php
class AttributeRight {

	public $tag;
	public $tagName;
	public $tagId;
	public $options;
	public $attributeTag;
	public $opt;


	public function setAttrRightArr($tag,$tagName,$tagId,$options,$attributeTag,$opt){
		$this->tag = $tag;
		$this->tagName = $tagName;
		$this->tagId = $tagId;
		$this->options = $options;
		$this->attributeTag = $attributeTag;
		$this->opt =  $opt;
		
	}

	/*
	public function setRefAppObject($refAppObjId,$refAppAttrId){
		$this->objectId = $refAppObjId;
		$this->attributeId = refAppAttrId;
		
	}

	public function getRefAppObjectId(){
		return $this->objectId;
	}

	public function getRefAppAttributeId(){
		return $this->attributeId;
	}
	*/    

	public function getTag(){
		return $this->tag; 
	}

	public function getOpt(){
		return $this->opt; 
	}


	public function getTagName(){
		return $this->tagName; 
	}


	public function getTagId(){
		return $this->tagId; 
	}

	public function getOptions(){
		return $this->options; 
	}

	public function getAttributeTag(){
		return $this->attributeTag; 
	}

	private $refAppObjId;
	public function setRefAppId($appId){
		$this->refAppObjId = $appId;	
	}


	public function getRefAppId(){
		return $this->refAppObjId;
	}




	public function setRefAppAttributeId($refAttrId){
		$this->refAppObjId = $refAttrId;	
	}


	public function getassociatedRefField(){
		return $this->refAppObjId;
	}



	
}
