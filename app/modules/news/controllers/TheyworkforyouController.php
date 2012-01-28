<?php
/** Controller for accessing they work for you based news
*
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class News_TheyworkforyouController extends Pas_Controller_Action_Admin {

	const TWFYURL = 'http://www.theyworkforyou.com/api/';

	protected $_apiKey;

	protected $_cache = NULL;

	protected $_remove = array(
        'Airdrie and Shotts','Ayr, Carrick and Cumnock',
	'Belfast North','Belfast East','Belfast South',
	'Belfast West','Aberdeen North', 'Aberdeen South',
	'Berwick-upon-Tweed','Dundee East','Dundee West',
	'Dunfermline and West Fife', 'Berwickshire, Roxburgh and Selkirk','Banff and Buchan',
	'Caithness, Sutherland and Easter Ross','Cumbernauld,Kilsyth and Kirkintilloch East',
	'Dumfriesshire, Clydesdale and Tweeddale','Dumfries and Galloway',
	'East Kilbride, Strathaven and Lesmahagow','East Londonderry','East Antrim',
	'East Dunbartonshire','East Londonderry','East Lothian',
	'East Renfrewshire', 'Edinburgh East','Edinburgh North and Leith',
	'Edinburgh South','Edinburgh South West','Edinburgh West',
	'Falkirk','Fermanagh and South Tyrone','Foyle',
	'Glasgow Central','Glasgow East','Glasgow North',
	'Glasgow North East', 'Glasgow North West','Glasgow South',
	'Glasgow South West','Glenrothes','Inverclyde',
	'Inverness, Nairn, Badenoch and Strathspey', 'Kilmarnock and Loudoun',
	'Kirkcaldy and Cowdenbeath','Lanark and Hamilton East','Mid Ulster',
	'Midlothian', 'Na h-Eileanan an Iar','Newry and Armagh',
	'North Antrim','North Down','North East Fife',
	'Ochil and South Perthshire', 'Paisley and Renfrewshire North','Paisley and Renfrewshire South',
	'Ross, Skye and Lochaber','Rutherglen and Hamilton West','South Antrim',
	'Upper Bann','West Aberdeenshire and Kincardine',
	'West Dunbartonshire','West Tyrone','Lagan Valley',
	'Strangford'
	);

	public function init() {
 	$this->_helper->_acl->allow(null);
      	$this->_helper->contextSwitch()
            ->setAutoDisableLayout(true)
            ->addContext('kml',array('suffix' => 'kml'))
            ->addContext('rss',array('suffix' => 'rss'))
            ->addContext('atom',array('suffix' => 'atom'))
            ->addActionContext('finds', array('xml','json','kml','rss','atom'))
            ->addActionContext('members',array('xml','json'))
            ->addActionContext('constituencies',array('xml','json'))
            ->addActionContext('index',array('xml','json'))
             ->initContext();
        $frontendOptions = array('lifetime' => 31556926, 'automatic_serialization' => true);
        $backendOptions = array('cache_dir' => 'app/cache/twfy');
        $this->_cache = Zend_Cache::factory('Output','File',$frontendOptions,$backendOptions);
        $this->_apiKey = $this->_helper->config()->webservice->twfy->apikey;
        }


	private function get($query){
	$config = array(
        'adapter'   => 'Zend_Http_Client_Adapter_Curl',
        'curloptions' => array(
            CURLOPT_POST =>  true,
            CURLOPT_USERAGENT =>  $_SERVER["HTTP_USER_AGENT"],
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_LOW_SPEED_TIME => 1
            ),
	);
	$client = new Zend_Http_Client(self::TWFYURL . $query, $config);
	$response = $client->request();

	$code = $this->getStatus($response);
	if($code == true){
	return $response->getBody();
	} else {
	return NULL;
	}

	}

	private function getStatus($response) {
        $code = $response->getStatus();
        switch($code) {
            case ($code == 200):
                    return true;
                    break;

            case ($code == 404):
                    throw new Exception('The resource could not be found');
                    break;
            case ($code == 406):
                    throw new Exception('You asked for an unknown representation');
                    break;
            default;
                    return false;
                    break;
        }
	}


        /** Retrieve the page number
        *
        */
        public function getPage(){
        $page = $this->_getParam('page');
	if(!isset($page)){
		$start = 1;
	} else {
		$start = $page ;
	}
	return $start;
        }
	public function indexAction() {
	$page = $this->getPage();
	$term = $this->_getParam('term');
        $search = $term ? $term : 'portable antiquities scheme';
        $method = 'getHansard?';
        $params = array(
            'key' => $this->_apiKey,
            'output' => 'js',
            'order' => 'd',
            'search' => $search,
            'num' => 20,
            'page' => $page
        );
	$twfy = $method . http_build_query($params);

	$key = md5($twfy);

	if (!($this->_cache->test($key))){
        $arts = $this->get($twfy);
        $this->_cache->save($arts);
	} else {
	$arts = $this->_cache->load($key);
	}

	$arts = Zend_Json_Decoder::decode($arts, Zend_Json::TYPE_OBJECT);

	$data = array();

	foreach($arts->rows as $row){
		$data[] = get_object_vars($row);
	}

	$pagination = array(
	'page'          => (int)$page,
	'perpage'      => (int)$arts->info->results_per_page,
	'total_results' => (int)$arts->info->total_results
	);

	$paginator = Zend_Paginator::factory($pagination['total_results']);
	$paginator->setCurrentPageNumber($pagination['page'])
			->setItemCountPerPage($pagination['perpage'])
			->setCache($this->_cache);
	$this->view->data = $data;
	$this->view->paginator = $paginator;
	}

	public function mpAction() {
	if($this->_getParam('id',false)) {
        $method = 'getPerson?';
        $params = array(
        'key'       =>  $this->_apiKey,
        'output'    =>  'js',
        'id'        =>  $this->_getParam('id')
        );
	$twfy = $method . http_build_query($params);
	if (!($this->_cache->test(md5($twfy)))) {
	$data = $this->get($twfy);
	$this->_cache->save($data);
	} else {
	$data = $this->_cache->load(md5($twfy));
	}
	$this->view->data = Zend_Json_Decoder::decode($data, Zend_Json::TYPE_OBJECT);
	} else {
	throw new Pas_Exception_Param($this->_missingParameter);
	}
	}

	public function findsAction(){
	if($this->_getParam('constituency',false)){
	$method = 'getGeometry?';
	$params = array(
		'key' => $this->_apiKey,
		'name' => $this->_getParam('constituency'),
		'output' => 'js'
	);
	$this->view->constituency = $this->_getParam('constituency');
	$twfy = $method . http_build_query($params);
	if (!($this->_cache->test(md5($twfy)))) {
	$data = $this->get($twfy);
	$this->_cache->save($data);
	} else {
	$data = $this->_cache->load(md5($twfy));
	}
	$cons = Zend_Json_Decoder::decode($data, Zend_Json::TYPE_OBJECT);

	$bbox = array($cons->min_lat, $cons->min_lon, $cons->max_lat, $cons->max_lon);
	$search = new Pas_Solr_Handler('beowulf');
        $search->setFields(array(
    	'id', 'identifier', 'objecttype',
    	'title', 'broadperiod','imagedir',
    	'filename','thumbnail','old_findID',
    	'description', 'county')
        );
        $search->setFacets(array('objectType','county','broadperiod'));
	$search->setParams(array('bbox' => implode(',',$bbox)));
        $search->execute();

        $this->view->facets = $search->_processFacets();
        $this->view->paginator = $search->_createPagination();
        $this->view->finds = $search->_processResults();
	} else {
	throw new Pas_Exception_Param($this->_missingParameter);
	}
 	}

	public function constituenciesAction() {
	$page = $this->getPage();

	$params = array(
		'key' => $this->_apiKey,
		'output' => 'js',
		'date'	=> '2010-05-07'
	);
	$method = 'getConstituencies?';

	$twfy = $method . http_build_query($params);

	if (!($this->_cache->test(md5($twfy)))) {
	$data = $this->get($twfy);
	$this->_cache->save($data);
	} else {
	$data = $this->_cache->load(md5($twfy));
	}
	$data = Zend_Json_Decoder::decode($data, Zend_Json::TYPE_OBJECT);

	foreach ($data as $a) {
	if(in_array($a->name,$this->_remove)){
	unset($a->name);
	}
	}
	$data2 = array();
	foreach($data as $a){
	if(isset($a->name)){
	$data2[] = array('name' => $a->name);
	}
	}
	$paginator = new Zend_Paginator(new Zend_Paginator_Adapter_Array($data2));
	$paginator->setCurrentPageNumber($this->getPage())
			->setItemCountPerPage(40)
			->setCache($this->_cache);
	$this->view->data = $paginator;
	}

	public function membersAction() {
	$method = 'getMps?';
	$params = array(
		'key' => $this->_apiKey,
		'output' => 'js',
	);
	$twfy = $method . http_build_query($params);
	if (!($this->_cache->test(md5($twfy)))) {
	$data = $this->get($twfy);
	$this->_cache->save($data);
	} else {
	$data = $this->_cache->load(md5($twfy));
	}
	$data = Zend_Json_Decoder::decode($data, Zend_Json::TYPE_OBJECT);
	$data2 = array();
	foreach ($data as $a){
	if(in_array($a->constituency,$this->_remove)){
	unset($a);
	}
	if(isset($a->name)){
	$data2[] = get_object_vars($a);
	}
	}
	$paginator = new Zend_Paginator(new Zend_Paginator_Adapter_Array($data2));
        $paginator->setCurrentPageNumber((int)$this->getPage());
	$paginator->setItemCountPerPage(30)
    	      ->setPageRange(10)
    	      ->setCache($this->_cache);
   	if(in_array($this->_helper->contextSwitch()->getCurrentContext(), array('xml','json'))){
	$this->view->data = $data;
   	$members = array();
   	foreach($paginator as $k => $v){
   	$members[]=array();
	$members[$k] = $v;
   	}
   	$this->view->members = $members;
   	} else {
	$this->view->data = $paginator;
   	}
	}

}
