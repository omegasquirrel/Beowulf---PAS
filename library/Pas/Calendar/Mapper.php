<?php
class Pas_Calendar_Mapper 
{

	protected $_username;
	protected $_password;
	protected $_config;
	protected $_service;
	protected $_calendar;
	protected $_timezone = '0000:00:00-00:00';
	protected $_today;
	protected $_tonight;
	
	
	private static $_instance = NULL;
	
	public function __construct()
	{
	$this->_config = Zend_Registry::get('config');
    $this->_username = $this->_config->webservice->google->username;
    $this->_password = $this->_config->webservice->google->password;
    $this->_service  = Zend_Gdata_Calendar::AUTH_SERVICE_NAME;
	}
	
	public function _setToday($today)
	{
		if(!isset($today)){
		$this->_today = date('Y-m-d');
		} else {
			$this->_today = $today;
		}	
	}
	
	public function _setTonight($tonight)
	{
		if(isset($tonight)){
		$this->_tonight = date('Y-m-d' . strtotime($this->_today * 24*60*60)) . $this->_timezone;
		} else {
			$this->_tonight = $tonight;
		}
	}
	
	public function _getToday()
	{
		return $this->_today;
	}
	
	public function _getTonight()
	{
		return $this->_tonight;
	}	
	
	public function getCalendar(){
	$client = Zend_Gdata_ClientLogin::getHttpClient(
		$this->_username, $this->_password, $this->_service
		);
	// Create an instance of the Calendar service
	return new Zend_Gdata_Calendar($client);	
	}
	
	public function getCalendarList()
	{
    return $this->getCalendar()->getCalendarListFeed();
	}
	
	public function getEvent($id)
	{
		$query = $this->getCalendar()->newEventQuery();
		$query->setUser('default');
		$query->setVisibility('private');
		$query->setProjection('full');
		$query->setEvent($id);
		return $this->getCalendar()->getCalendarEventEntry($query);
	}
	
	
	public function getEventFeed()
	{
	$query = $this->getCalendar()->newEventQuery();
	$query->setUser('default');
	$query->setVisibility('private');
	$query->setProjection('full');
	$query->setOrderby('starttime');
	$query->setFutureevents('true');
	$query->setSortOrder('a');
	return $this->getCalendar()->getCalendarEventFeed($query);
	}
	
	private function getExtendedProperty( Zend_Gdata_Calendar_EventEntry $event, $name)
	{
		$extendedProperties = $event->extendedProperty();
		foreach ( $extendedProperties as $extendedProperty){
			if($name == $extendedProperty->name){
				return $extendedProperty->value;
			}
		}
	}

	private function addExtendedProperty( Zend_Gdata_Calendar_EventEntry $event, $name, $value)
	{
		$extendedProperty = $this->getCalendar()->newExtendedProperty( $name, $value);
		$extendedProperties = array_merge($event->extendedProperty, array ($extendedProperty));
		$event->extendedProperty = $extendedProperties;
		$eventNew = $event->save();
		return $eventNew;
	}
	
	public function getEventById( $id )
	{
		$query = $this->getCalendar()->newEventQuery()->setUser('default')
									 ->setVisibility('private')
									 ->setProjection('full')
									 ->setEvent($id);
		return $this->getCalendar()->getCalendarEventEntry( $query );
	}
	
	public function getEvents( $startTime, $endTime, $maximum = 25)
	{
		$query = $this->getCalendar()->newEventQuery()->setUser('default')
									 ->setVisibility('private')
									 ->setStartMin( $startTime )
									 ->setStartMax( $endTime )
									 ->setMaxResults( $maximum );
		$eventFeed = $this->getCalendar()->getCalendarEventFeed( $query );
		$events = array();
		foreach( $eventFeed as $event ){
			$event = new Pas_Calendar_Model_Event( array( 
				'id'          => substr($event->id,strrpos($event->id,'/')+1,26), //(the id is 26 charachters long, sometimes followed by a time variable)
            	'name'        => $event->title,
            	'description' => $event->content,
            	'when'        => $event->when(),
			));
		}
	}
	
	public function getInstance()
	{
		
	}
}