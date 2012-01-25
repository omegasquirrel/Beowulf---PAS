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

	protected $_remove = array('Airdrie and Shotts','Ayr, Carrick and Cumnock',
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

	private function get($url){
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
	$request = $url;
	$client = new Zend_Http_Client($request, $config);
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
        $service = 'getHansard?';
        $params = array(
            'key' => $this->_apiKey,
            'output' => 'js',
            'order' => 'd',
            'search' => $search,
            'num' => 20,
            'page' => $page
        );
	$twfy = http_build_query($params);

        $key = md5($twfy);

	if (!($this->_cache->test($key))){
        $arts = $this->get(self::TWFYURL . $service . $twfy);
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
	$id = $this->_getParam('id');
	if($this->_getParam('id',false)) {
	if (!($this->_cache->test('mpdetails'.$id))) {
        $service = 'getPerson?';
        $params = array(
            'key'       =>  $this->_apiKey,
            'output'    =>  'js',
            'order'     =>  'd',
            'num'       =>  20,
            'page'      =>  $page,
            'id'        =>  $this->_getParam('id')
        );
	$twfy = http_build_query($params);
        $data = $this->get(self::TWFYURL . $service . $twfy);
	$this->_cache->save($data);
	} else {
	$data = $this->_cache->load('mpdetails'.$id);
	}

	$this->view->data = $data;

	} else {
	throw new Pas_Exception_Param($this->_missingParameter);
	}
	}

	public function findsAction(){
	if($this->_getParam('constituency',false)){
	ini_set("memory_limit","256M");
	$params = $this->_getAllParams();
	$this->view->constituency = $params['constituency'];
	$finds = new Finds();
	$finds = $finds->getFindsConstituency($params['constituency']);
	$this->cs = $this->_helper->contextSwitch();
	$kml = array('kml');
	if(!in_array($this->cs->getCurrentContext(),$kml )) {
	$paginator = Zend_Paginator::factory($finds);
	$paginator->setItemCountPerPage(30)
	          ->setPageRange(20);
	if(isset($params['page']) && ($params['page'] != "")) {
    $paginator->setCurrentPageNumber((int)$params['page']);
	}
	$data = array(
	'pageNumber' => $paginator->getCurrentPageNumber(),
	'total' => number_format($paginator->getTotalItemCount(),0),
	'itemsReturned' => $paginator->getCurrentItemCount(),
	'totalPages' => number_format($paginator->getTotalItemCount()/
	$paginator->getItemCountPerPage(),0)
	);
	$this->view->paging = $data;
	$contexts = array('json');
	if(in_array($this->cs->getCurrentContext(),$contexts )) {
	$findsjson = array();
	foreach($paginator as $k => $v) {
	$findsjson[$k] = $v;
	}
	$this->view->objects = array('object' => $findsjson);
	} else {
	$this->view->finds = $paginator;
	}
	} else {
	$this->view->finds = $finds;
	}
	} else {
	throw new Pas_Exception_Param($this->_missingParameter);
	}
 	}

	public function constituenciesAction() {
	$page = $this->_getParam('page');
	if (!($this->_cache->test('const'))) {
	$query = 'getConstituencies?date=2010-05-07';
	$output = '&output=xml';
	$key = '&key='.$this->_apiKey;
	$twfy = self::TWFYURL.$query.$output.$key;
	$data = Zend_Json::fromXml($this->get($twfy),true);
	$this->_cache->save($data);
	} else {
	$data = $this->_cache->load('const');
	}
	$data = json_decode($data);
	$data2 = array();
	foreach ($data->twfy->match as $a) {
	if(in_array($a->name,$this->_remove)){
	unset($a->name);
	}
	if(isset($a->name)){
	$data2[] = array('name' => $a->name);
	}
	}
	$paginator = new Zend_Paginator(new Zend_Paginator_Adapter_Array($data2));
	if(isset($page) && ($page != "")) {
    $paginator->setCurrentPageNumber((int)$page);
	}
	$paginator->setItemCountPerPage(40)
    	      ->setPageRange(10);
	if(in_array($this->_helper->contextSwitch()->getCurrentContext(),array('xml','json'))){
   	$data = array('pageNumber' => $paginator->getCurrentPageNumber(),
				  'total' => number_format($paginator->getTotalItemCount(),0),
				  'itemsReturned' => $paginator->getCurrentItemCount(),
				  'totalPages' => number_format($paginator->getTotalItemCount()/
   				$paginator->getItemCountPerPage(),0));
	$this->view->data = $data;
   	$constituencies = array();
   	foreach($paginator as $k => $v){
   	$constituencies[]=array();
	$constituencies[$k] = $v;
   	}
   	$this->view->constituencies = $constituencies;
   	} else {
	$this->view->data = $paginator;
   	}
	}

	public function membersAction() {
	$page = $this->_getParam('page');
	if (!($this->_cache->test('members'))) {
	$query = 'getMps';
	$output = '&output=xml';
	$key = '&key='.$this->_apiKey;
	$twfy = self::TWFYURL.$query.$output.$key;
	$data = Zend_Json::fromXml($this->get($twfy),true);
	$data = json_decode($data);
	$this->_cache->save($data);
	} else {
	$data = $this->_cache->load('members');
	}
	$data2 = array();
	foreach ($data->twfy->match as $a){
	if(in_array($a->constituency,$this->_remove)){
	unset($a->name);
	unset($a->person_id);
	unset($a->party);
	unset($a->constituency);
	}
	if(isset($a->name)){
	$data2[] = array('name' => $a->name,
	'person_id' => $a->person_id,
	'constituency' => $a->constituency,
	'party' => $a->party
	);
	}
	}
	$paginator = new Zend_Paginator(new Zend_Paginator_Adapter_Array($data2));
	if(isset($page) && ($page != "")) {
    $paginator->setCurrentPageNumber((int)$page);
	}
	$paginator->setItemCountPerPage(40)
    	      ->setPageRange(10);
   	if(in_array($this->_helper->contextSwitch()->getCurrentContext(),array('xml','json'))){
   	$data = array('pageNumber' => $paginator->getCurrentPageNumber(),
				  'total' => number_format($paginator->getTotalItemCount(),0),
				  'itemsReturned' => $paginator->getCurrentItemCount(),
				  'totalPages' => number_format($paginator->getTotalItemCount()/
   												$paginator->getItemCountPerPage(),0));
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
