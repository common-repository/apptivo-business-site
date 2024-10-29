<?php
class tableSectionAttributesRetrieval {
	
	
	public function retrieveTableSectionAttribute($tablesectionObj,$sectionName,$sectionId,$labelName,$attributes, $fieldArr){
		if(array_key_exists($labelName, $fieldArr[$sectionName])){
			$documents = $fieldArr[$sectionName][$labelName];
			
			for($i=0;$i<sizeof($documents);$i++){
				$row = new stdClass();
				$columns = array();
				$row->index = $i;
				$row->customAttributeId = "apptivo_table_attribute_row_".rand();
				
				foreach($attributes as $attribute){
					$column = new stdClass();
					
					$tagName = $attribute->rightData[0]->tagName;
					if($attribute->rightData[0]->tag == "simpleTextarea"){
						$column->id = $row->customAttributeId.'_'.$attribute->attributeId;
						$column->customAttributeId = $attribute->attributeId;;
						$column->customAttributeName = $tagName;
						$column->customAttributeTagName = $tagName;
						$column->customAttributeValue = "";
						$column->customAttributeValueId = "";
						$column->customAttributeType = "simpleTextarea";
						$column->$tagName = "";
						$columns[] = $column;
					}else if($attribute->rightData[0]->tag == "fileUpload"){
						$column->id = $row->customAttributeId.'_'.$attribute->attributeId;;
						$column->customAttributeId = $attribute->attributeId;;
						$column->customAttributeName = $tagName;
						$column->customAttributeTagName = $tagName;
						$column->customAttributeValue = "";
						$column->customAttributeValueId = "";
						$column->customAttributeType = "fileUpload";
						
						
						$docArrValues = array();
						$docus = new stdClass();
						$docus->documentId = $documents[$i]->documentId;
						$docus->documentKey= $documents[$i]->documentKey;
						$docus->documentName=$documents[$i]->documentName;
						$docus->documentSize=$documents[$i]->documentSize;
						$docus->size=$documents[$i]->size;
						$docArrValues[]= $docus;
						
						$column->$tagName = $docArrValues;
						
						$docAttValues = array();
						$docAttObj = new stdClass();	
						$docAttObj->attributeId = $documents[$i]->documentId.'||'.$documents[$i]->documentKey;
						$docAttObj->attributeValue=$documents[$i]->documentName;
						$docAttValues[] = $docAttObj;
						
						$column->attributeValues = $docAttValues;
						$columns[] = $column;
						
					}
					
					
			}		
			
			$row->columns = $columns;
			$rows[] = $row;	
			
			
			
		}
	}
		
	$tablesectionObj->rows = $rows;
	return $tablesectionObj;
 }
	
	
}
?>