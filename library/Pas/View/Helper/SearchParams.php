<?php
/**
 * This class is to display search params
 * Sucks monkey balls in extremis.
 * Load of rubbish, needs a rewrite
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @uses Zend_View_Helper_Abstract
 * @author Daniel Pett
 * @since September 13 2011
 * @todo change the class to use zend_navigation
*/
class Pas_View_Helper_SearchParams
	extends Zend_View_Helper_Abstract {

	protected $_cache;

	public function __construct(){
		$this->_cache = Zend_Registry::get('cache');
	}

	protected $_niceNames = array(
	'mintName' => 'Mint',
	'rulerName' => 'Ruler',
	'objectType' => 'Object type',
	'q' => 'Free text search',
	'fourFigure' => 'Four figure NGR',
	'old_findID' => 'Find number',
	'discovered' => 'Discovery year',
	'TID' => 'Treasure case number',
	'hID' => 'Hoard ID',
	'otherref' => 'other reference',
	'smrRef' => 'SMR or HER reference number',
	'typeID' => 'Medieval periodic type',
	'cciNumber' => 'Celtic coin Index number',
	'broadperiod' => 'Broad period',
	'objecttype' => 'Object type',
	'rallyID' => 'Rally known as',
        'woeid' => 'Yahoo!\'s Where on Earth ID number',
        'd' => 'Distance (in kilometres)',
        'lat' => 'latitude',
        'lon' => 'longitude',
        'bbox' => 'Bounding box co-ordinates'
	);

	public function SearchParams($params = NULL) {

	$params = array_slice($params,3);
	if(array_key_exists('page',$params)){
		unset($params['page']);
	}

	$params = $this->cleanParams($params);
	$html = '';
	if(!is_null($params)) {
	$html .= '<p>You searched for: </p>';
	$html .= '<ul>';

	foreach($params as $k => $v){
		$html .= '<li>' . $this->cleanKey($k) .': ' . $v . '</li>';
		$this->view->headTitle(  ' > ' . $this->cleanKey($k) . ': ' . $this->view->escape($v));
	}

	$html .= '</ul>';
	}
	return $html;
	}
	public function cleanKey($string){
	if(in_array($string,array_keys($this->_niceNames))){
	$text = "$string";
	foreach ($this->_niceNames as $key => $value) {
	$text = preg_replace( "|(?!<[^<>]*?)(?<![?.&])\b$key\b(?!:)(?![^<>]*?>)|msU",
	$value , $text );
	}
	} else {
	$text = $string;
	}
	return ucfirst($text);
	}

	public function getData($name, $field, $value){
	$key = md5($name.$field.$value);
	if (!($this->_cache->test($key))) {
	$model = new $name();
	$data = $model->fetchRow($model->select()->where('id = ?', $value))->$field;
	$this->_cache->save($data);
	} else {
	$data = $this->_cache->load($key);
	}
	return $data;
	}

	public function cleanParams($params){
	foreach($params as $key => $value){
	switch($key){
		case 'regionID':
			$params[$key] = $this->getData('Regions','region', $value);
			break;
		case 'denomination':
			$params[$key] = $this->getData('Denominations','denomination', $value);
			break;
		case 'ruler':
			$params[$key] = $this->getData('Rulers','issuer', $value);
			break;
		case 'mint':
			$params[$key] = $this->getData('Mints','mint_name', $value);
			break;
		case 'material':
			$params[$key] = $this->getData('Materials','term', $value);
			break;
		case 'hID':
			$params[$key] = $this->getData('Hoards','term', $value);
			break;
		case 'treasure' :
			$params[$key] = yes;
			break;
		case 'rally' :
			$params[$key] = yes;
			break;
		case 'note':
			$params[$key] = yes;
			break;
		case 'hoard':
			$params[$key] = yes;
			break;
                case 'thumbnail':
			$params[$key] = 'Only object with images please';
			break;
		case 'surface':
			$params[$key] = $this->getData('Surftreatments','term', $value);
			break;
		case 'workflow':
			$params[$key] = $this->getData('Workflows','workflowstage', $value);
			break;
		case 'manufacture':
			$params[$key] = $this->getData('Manufactures','term', $value);
			break;
		case 'decoration':
			$params[$key] = $this->getData('Decmethods','term', $value);
			break;
		case 'category':
			$params[$key] = $this->getData('CategoriesCoins','category', $value);
			break;
		case 'reason':
			$params[$key] = $this->getData('Findofnotereasons','term', $value);
			break;
		case 'type':
			$params[$key] = $this->getData('MedievalTypes','type', $value);
			break;
		case 'rallyID':
			$params[$key] = $this->getData('Rallies','rally_name', $value);
			break;
		default:
			$params[$key] = $value;
			break;
	}
	}
	return $params;
	}



	}