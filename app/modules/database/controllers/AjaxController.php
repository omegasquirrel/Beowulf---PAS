<?php
/** Controller for displaying various ajax request pages
*
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Database_AjaxController extends Pas_Controller_Action_Ajax {

    /** Setup the contexts by action and the ACL.
    */
    public function init() {
	$this->_helper->_acl->allow('public',NULL);
	$this->_helper->_acl->deny('public',array('nearest', 'kml', 'her', 'gis'));
	$this->_helper->_acl->deny('member',array('nearest', 'kml', 'her', 'gis'));
	$this->_helper->_acl->allow('flos',NULL);
	$this->_helper->_acl->allow('hero',NULL);
	$this->_helper->_acl->allow('research',NULL);
	$this->_helper->layout->disableLayout();
    }

    const REDIRECT = '/database/artefacts/';

    /** Redirect as no direct access
     *
     */
    public function indexAction() {
        $this->_redirect(self::REDIRECT);
    }

    /** Display the webcitation page
     *
     */
    public function webciteAction()	{
    if($this->_getParam('id',false)){
    $finds = new Finds();
    $this->view->finds = $finds->getWebCiteFind((int)$this->_getParam('id'));
    } else {
	throw new Pas_Exception_Param($this->_missingParameter);
    }
    }

    /** Display the find embed view
     *
    */
    public function embedAction() {
    if($this->_getParam('id',false)){
    $id = (int)$this->_getParam('id');
    $finds = new Finds();
    $this->view->finds = $finds->getEmbedFind($id);
    $thumbs = new Slides;
    $this->view->thumbs = $thumbs->getThumbnails($id);
    } else {
	throw new Pas_Exception_Param($this->_missingParameter);
    }
    }

    /** Display other discoveries
    */
    public function otherdiscoveriesAction() {
    $id = $this->_getParam('id');
    $finds = new Finds;
    $this->view->finds = $finds->getOtherFinds($id);
    $quants = new Finds;
    $this->view->quants = $quants->getOtherFindsTotals($id);
    }

    /** Retrieve the nearest finds to a lat lon point
     *
     */
    public function nearestAction() {
    $lat = $this->_getParam('lat');
    $long = $this->_getParam('long');
    $distance = (int)$this->_getParam('distance');
    $finds = new Finds();
    $this->view->finds = $finds->getByLatLong($lat,$long,$distance);
    $this->view->distance = $distance;
    $this->view->lat = $lat;
    $this->view->long = $long;
    }

    /** Download a file
    */
    public function downloadAction() {
    if($this->_getParam('id',false)) {
    $images = new Slides();
    $download = $images->getFileName($this->_getParam('id'));
    foreach($download as $d) {
    $filename = $d['f'];
    $path = $d['imagedir'];
    }
    $file = './' . $path . $filename;
    $mime_type = mime_content_type($file);
    if (file_exists($file)) {
    $this->_helper->viewRenderer->setNoRender();
    $this->_helper->sendFile($file,$mime_type);
    } else {
        throw new Pas_Exception_Param('That file does not exist',404);
    }
    } else {
	throw new Pas_Exception_Param($this->_missingParameter,500);
    }
    }

    /** Display rally data
    */
    public function rallydataAction() {
    $rallies = new Rallies();
    $this->view->mapping = $rallies->getMapdata();
	$this->getResponse()->setHeader('Content-type', 'text/xml');
    }

    /** Display period tag cloud
    */
    public function tagcloudAction() {
    $periods = new Periods();
    $this->view->periods = $periods->getPeriodDetails($this->_getParam('id'));
    $this->view->objects = $periods->getObjectTypesByPeriod($this->_getParam('id'));
    }

    /** Record data overlay page
    */
    public function recordAction() {
    if($this->_getParam('id',false)) {
    $this->view->recordID = $this->_getParam('id');
    $id = $this->_getParam('id');
    $finds = new Finds();
    $findsdata = $finds->getIndividualFind($id,$this->getRole());
    if(count($findsdata)) {
    $this->view->finds = $findsdata;
    } else {
	throw new Pas_Exception_NotAuthorised('You are not authorised to view this record');
    }
    $findsdata = new Finds();
    $this->view->findsdata = $findsdata->getFindData($id);
    $this->view->findsmaterial = $findsdata->getFindMaterials($id);
    $this->view->temporals = $findsdata->getFindTemporalData($id);
    $this->view->peoples = $findsdata->getPersonalData($id);
    $rallyfind = new Rallies;
    $this->view->rallyfind = $rallyfind->getFindRallyNames($id);
    $coins = new Coins;
    $this->view->coins = $coins->getCoinData($id);
    $thumbs = new Slides;
    $this->view->thumbs = $thumbs->getThumbnails($id);
    $refs = new Publications;
    $this->view->refs = $refs->getReferences($id);
    }else {
	throw new Pas_Exception_Param($this->_missingParameter,500);
    }
    }

    /** Display a report in pdf format
    */
    public function reportAction() {
    if($this->_getParam('id',false)) {
    $this->view->recordID = $this->_getParam('id');
    $id = $this->_getParam('id');
    $finds = new Finds();
    $findsdata = $finds->getIndividualFind($id,$this->getRole());
    if(count($findsdata)) {
        $this->view->finds = $findsdata;
    } else {
        throw new Pas_Exception_NotAuthorised('You are not authorised to view this record');
    }
    $findsdata = new Finds();
    $this->view->findsdata = $findsdata->getFindData($id);
    $this->view->findsmaterial = $findsdata->getFindMaterials($id);
    $this->view->temporals = $findsdata->getFindTemporalData($id);
    $this->view->peoples = $findsdata->getPersonalData($id);
    $rallyfind = new Rallies;
    $this->view->rallyfind = $rallyfind->getFindRallyNames($id);
    $coins = new Coins;
    $this->view->coins = $coins->getCoinData($id);
    $thumbs = new Slides;
    $this->view->thumbs = $thumbs->getThumbnails($id);
    $refs = new Publications;
    $this->view->refs = $refs->getReferences($id);
    $findspotsdata = new Findspots();
    $this->view->findspots = $findspotsdata->getFindSpotData($id);
    } else {
	throw new Pas_Exception_Param($this->_missingParameter,500);
    }
    }

    /** Get a find autdit overlay
     *
     *
     */
    public function auditAction() {
    $audit = new FindsAudit();
    $this->view->audit = $audit->getChange($this->_getParam('id'));
    }

    /** Get a findspot overlay from the audit table
     *
     */
    public function fsauditAction(){
    $audit = new FindSpotsAudit();
    $this->view->audit = $audit->getChange($this->_getParam('id'));
    }

    /** Get a coin overlay from the audit table
    */
    public function coinauditAction(){
    $audit = new CoinsAudit();
    $this->view->audit = $audit->getChange($this->_getParam('id'));
    }

    /** Get a saved search overlay
     *
     */

    public function savesearchAction() {
    $form = new SaveSearchForm();
    $this->view->form = $form;
    }

    /** Copy the last find
    */
    public function copyfindAction() {
    $finds = new Finds();
    $finddata = $finds->getLastRecord($this->getIdentityForForms());
    $this->_helper->layout->disableLayout();
    $this->_helper->viewRenderer->setNoRender();
    echo Zend_Json::encode($finddata);
    }

    public function mapdataAction(){

//        $this->_helper->viewRenderer->setNoRender();
	$this->_helper->layout->disableLayout();
        $params = $this->_getAllParams();
	$params['show'] = 2000;
	$params['format'] = 'json';
	$search = new Pas_Solr_Handler('beowulf');
	$search->setFields(array(
		'id','old_findID','description', 'gridref','fourFigure',
		'longitude', 'latitude', 'county', 'woeid',
		'district', 'parish','knownas', 'thumbnail'));
	$search->setParams($params);
	$search->execute();
        $this->view->results = $search->_processResults();
   }

   public function mapdata2Action(){
    $params = $this->_getAllParams();
    if(!isset($params['show'])){
	$params['show'] = 2000;
    }
	$params['format'] = 'json';
	$search = new Pas_Solr_Handler('beowulf');
	$search->setFields(array(
		'id','old_findID','description', 'gridref','fourFigure',
		'longitude', 'latitude', 'county', 'woeid',
		'district', 'parish','knownas', 'thumbnail'));
	$search->setParams($params);
	$search->execute();
	$this->view->results = $search->_processResults();
	$this->getResponse()->setHeader('Content-type', 'text/xml');
	}

	protected $_csvFields = array(
	'id','old_findID','secuid',
	'objecttype', 'classification', 'subclass',
	'length', 'height', 'width',
	'thickness', 'diameter', 'quantity',
	'other_ref', 'TID', 'broadperiod',
	'numdate1', 'numdate2', 'culture',
	'description', 'notes', 'reuse',
	'created', 'updated', 'workflow',
	'note', 'datefound1','datefound2',
	'inscription', 'disccircum', 'museumAccession',
	'subsequentAction', 'subPeriodFrom', 'subPeriodTo',
	'obverse_description', 'obverse_inscription', 'reverse_description',
	'reverse_inscription', 'denomination', 'degree_of_wear',
	'allen_type', 'va_type', 'mack',
	'reeceID','dieAxis', 'wearID',
	'moneyer', 'revtypeID',	'categoryID',
	'typeID', 'tribeID', 'status',
	'rulerQualifier', 'denominationQualifier', 'mintQualifier',
	'dieAxisCertainty', 'initialMark', 'reverseMintMark',
	'statusQualifier', 'reason', 'username',
	'fullname', 'institution', 'usernameUpdate',
	'fullnameUpdate', 'primaryMaterial', 'secondaryMaterial',
	'decoration	style', 'manufacture', 'surfaceTreatment',
	'completeness', 'preservation', 'periodFrom',	
	'periodTo', 'discmethod','tribe',	
	'region', 'area','ruler1',	
	'ruler2', 'period_name	date_range',	
	'mint_name', 'wear','category',
	'type', 'reverseType', 'finder',
	'identifier', 'secondaryIdentifier', 'recorder',	
	'county', 'parish',	'district',	
	'knownas','gridref','fourFigure',	
	'easting', 'northing', 'map25k',	
	'map10k','gridlength','accuracy',	
	'address','postcode','findspotdescription',
	'lat','lon', 'source'
	);
	
	
   public function exporterAction(){
   	$this->_helper->layout->disableLayout();
    $params = $this->_getAllParams();
	$params['show'] = 15000;
	$params['format'] = 'json';
	$search = new Pas_Solr_Handler('beowulf');
	$search->setFields(array(
		'id','old_findID','description', 'gridref','fourFigure',
		'longitude', 'latitude', 'county', 'woeid',
		'district', 'parish','knownas', 'thumbnail'));
	$search->setParams($params);
	$search->execute();
    $this->view->results = $search->_processResults();
   }
   
   public function kmlAction(){
   $params = $this->_getAllParams();
	$params['show'] = 15000;
	$params['format'] = 'json';
	$search = new Pas_Solr_Handler('beowulf');
	$search->setFields(array(
		'id','old_findID','description', 'gridref','fourFigure',
		'longitude', 'latitude', 'county', 'woeid',
		'district', 'parish','knownas', 'thumbnail'));
	$search->setParams($params);
	$search->execute();
    $this->view->results = $search->_processResults();	
   }
   
   public function herAction(){
   	
   }
   
   public function iterateCsv($params, $page){
   	$params['show'] = 10;
	$params['format'] = 'json';
	$params['page'] = $page;
	
	$search = new Pas_Solr_Handler('beowulf');
	$search->setFields(array(
		'id','old_findID', 'objecttype', 'description', 'gridref',
		'fourFigure', 'longitude', 'latitude', 
		'county', 'woeid', 'district', 
		'parish','knownas', 'thumbnail',
		'easting', 'northing'));
	$search->setParams($params);
	$search->execute();
    $results = $search->_processResults();
    return $results;

   }
   
   public function csvAction(){
	$this->_helper->viewRenderer->setNoRender();
   	$params = $this->_getAllParams();
	$params['show'] = 10;
	$params['format'] = 'json';
	$search = new Pas_Solr_Handler('beowulf');
	$search->setParams($params);
	$search->setFields(array(
		'id','old_findID','description', 'gridref',
		'fourFigure', 'longitude', 'latitude', 
		'county', 'woeid', 'district', 
		'parish','knownas', 'thumbnail',
		'easting', 'northing'));
	$search->execute();
    $results = $search->_processResults();
    $paginator = $search->_createPagination();
    $pages = $paginator->getPages();
    $iterator = $pages->pageCount;
    
   	$csv = $this->arrayToCsv($results);
	$file = fopen('php://temp/maxmemory:'. (12*1024*1024), 'r+');
	fputcsv($file,array_keys($csv['0']),',','""');
   	foreach (range(1, $iterator) as $number) {
    $retrieved = $this->iterateCsv($this->_getAllParams(), $number);
   	$record = $this->arrayToCsv($retrieved);
    	foreach($record as $rec){
    	fputcsv($file, $rec, ',', '"');	
    	}
     
	}
      rewind($file);
      $output = stream_get_contents($file);
      fclose($file);
      $user = new Pas_User_Details();
      $username = $user->getPerson()->username;
      $time = Zend_Date::now()->toString('yyyyMMddHHmmss');
      $filename = 'PASExportFor_' . $username . '_' . $time . '.csv';
      $this->getResponse()->setHeader('Content-type', 'text/csv; charset=utf-8');
      $this->getResponse()->setHeader('Content-Disposition', 'attachment; filename=' . $filename);
      echo $output;
   
   }
   
	protected function arrayToCsv($data) {
    foreach ($data AS $dat) {
	$record = array();
	if(!array_key_exists('thumbnail',$dat)){
		$dat['thumbnail'] = NULL;
	}
	if(!array_key_exists('woeid',$dat)){
		$dat['woeid'] = NULL;
	}
	$record['id'] = $dat['id'];
	$record['old_findID'] = $dat['old_findID'];
	$record['objecttype'] = $dat['objecttype'];
	$record['description'] = $dat['description'];
	$record['thumbnail'] = $dat['thumbnail'];
	$record['gridref'] = $dat['gridref'];
	$record['fourFigure'] = $dat['fourFigure'];
	$record['latitude'] = $dat['latitude'];
	$record['longitude'] = $dat['longitude'];
	$record['easting'] = $dat['easting'];
	$record['northing'] = $dat['northing'];
	$record['county'] = $dat['county'];
	$record['district'] = $dat['district'];
	$record['parish'] = $dat['parish'];
	$record['knownas'] = $dat['knownas'];
	$record['woeid'] = $dat['woeid'];
	
 	foreach($dat as $k => $v){
		
	$record[$k] = trim(strip_tags(str_replace('<br />',array( "\n", "\r"), utf8_decode( $v ))));
	}
	$finalData[] = $record;
	}
    return $finalData;
	}  	
    
   public function gisAction(){
   	
   }
   
   public function osdataAction(){
   	$params = $this->_getAllParams();
	$params['show'] = 5489;
	$params['format'] = 'json';
	$params['source'] = 'osdata';
	$params['sort'] = 'id';
   $q = $this->_getParam('q');
	if(is_null($q)){
	$params['q'] = 'type:R OR type:A';
	} else {
		$params['q'] = 'type:R || type:A && ' . $q;
	}
	$search = new Pas_Solr_Handler('beogeodata');
	$search->setParams($params);
	$search->setFields(array('*'));
	$search->execute();
    $this->view->results =  $search->_processResults();	
   }
   
   public function smrsAction(){
   	$params = $this->_getAllParams();
	$params['show'] = 25046;
	$params['format'] = 'json';
	$params['sort'] = 'id';
	$params['source'] = 'smrdata';
	$search = new Pas_Solr_Handler('beogeodata');
	$search->setParams($params);
	$search->setFields(array('*'));
	$search->execute();
    $this->view->results =  $search->_processResults();	
   }
   
   public function peopleAction(){
   	$params = $this->_getAllParams();
	$params['show'] = 5000;
	$params['format'] = 'json';
	$params['sort'] = 'id';
	$search = new Pas_Solr_Handler('beopeople');
	$search->setParams($params);
	$search->setFields(array('*'));
	$search->execute();
    $this->view->results =  $search->_processResults();		
   }
}