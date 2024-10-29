<?php
class Section {
	
	/*setter */
	private $sectionId;
    public function setSecId($sectionId){
        $this->sectionId = $sectionId;
    }
    
	private $sectionLabel;
    public function setSecLabel($sectionLabel){
        $this->sectionLabel = $sectionLabel;
    }
    
    private $type;
	public function setType($type){
        $this->type = $type;
    }
    
    private $sectionType;
	public function setSectionType($sectionType){
        $this->sectionType = $sectionType;
    }
    
    private $isEnabled;
	public function setSecisEnabled($flag){
        $this->isEnabled = $flag;
    }
    
    private $attributeList;
    public function setAttributeList($attributeList){
    	$this->attributeList = $attributeList;
    	
    }

	
    /*getter */
    
	public function getSecLabel(){
        return $this->sectionLabel;
    }
    
	public function getSecId(){
        return $this->sectionId;
    }
    
	public function getType(){
        return $this->type;
    }
    
	public function getSectionType(){
        return $this->sectionType;
    }
    
	public function getSecisEnabled(){
        return $this->isEnabled;
    }
    public function getAttributeList(){
    	return $this->attributeList;
    }
    
}
