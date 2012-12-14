<?php

class Pas_Solr_FieldGeneratorFinds {
	
	protected $_context;
	
	public function __construct($context){
		$this->_context = $context;
	}

	public function getFields(){
	if(!in_array($this->_context,array('json', 'xml', 'kml', 'geojson'))){
		$fields = array(
		'id','identifier','objecttype',
        'title','broadperiod','description',
        'old_findID','thumbnail', 'county',
        'imagedir','filename', 'workflow', 
        'fourFigure', 'knownas', 'created', 'updated');
	} else {
		$fields = array(
		'id','identifier', 'objecttype',
		'creator', 'broadperiod', 'fromdate',
		'todate', 'description', 'notes',
		'materialTerm', 'cultureName', 
		'classification', 'subClassification', 
        'old_findID', 'regionName', 'county', 
        'district', 'parish', 'fourFigure',
		'knownas', 'imagedir', 'filename', 
		'thumbnail', 'denominationName', 'mintName',
		'obverseDescription', 'reverseDescription', 'obverseLegend',
		'reverseLegend', 'rulerName', 'tribeName',
		'cciNumber', 'mintmark', 'categoryTerm',
		'typeTerm' ,' axis', 'moneyerName', 'reverseType',
		'workflow', 'smrref', 'otherref',
		'TID', 'musaccno', 'created',
		'updated', 'weight', 'height', 
		'width', 'diameter', 'thickness',
		'quantity', 'length','created',
		'fourFigureLat', 'fourFigureLon'
		);
	}
	return $fields;
	}
	
}
