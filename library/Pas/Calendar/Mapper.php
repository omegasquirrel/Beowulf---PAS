<?php
class Pas_Calendar_Mapper 
{

	protected $_username;
	protected $_password;
	protected $_config;
	protected $_service;
	protected $_calendar;
	protected $_timezone = '00:00:00-00:00';
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
	
	public function getExtendedProperty( Zend_Gdata_Calendar_EventEntry $event)
	{
		$extendedProperties = $event->extendedProperty();
		foreach ( $extendedProperties as $extendedProperty){
				return $extendedProperty->value;
			}
	}

	private function addExtendedProperty( Zend_Gdata_Calendar_EventEntry $event, $properties)
	{
		$extendedProperty = array();
		foreach($properties as $key => $value){
		$extendedProperty[] = $this->getCalendar()->newExtendedProperty( $key, $value);
		}
		$extendedProperties = array_merge($event->extendedProperty, $extendedProperty);
		
		
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
	
		
	public function addEvent($data)
	{
		if(is_array($data)){
		$event= $this->getCalendar()->newEventEntry();
		$event->title = $this->getCalendar()->newTitle($data['title']);
		$event->where = array($this->getCalendar()->newWhere($data['location']));
		$event->content = $this->getCalendar()->newContent($data['content']);
		 
		// Set the date using RFC 3339 format.
		$startDate 	= $data['startDate'];
		$startTime 	= $data['startTime'];
		$endDate 	= $data['endDate'];
		$endTime 	= $data['endTime'];
		$tzOffset = "-00";
		 
		$when = $this->getCalendar()->newWhen();
		$when->startTime = "{$startDate}T{$startTime}:00.000{$tzOffset}:00";
		$when->endTime = "{$endDate}T{$endTime}:00.000{$tzOffset}:00";
		$event->when = array($when);
		// Upload the event to the calendar server
		// A copy of the event as it is recorded on the server is returned
		$newEvent = $this->getCalendar()->insertEvent($event);
		//Create extended properties
		$properties = array('type' => $data['eventType'], 'creator' => $data['creator']);
		$this->addExtendedProperty($newEvent, $properties);
		
		} else {
			throw new Pas_Calendar_Exception('The data supplied is not an array', 500);
		}
	}
	
	public function editEvent($data)
	{
	if(is_array($data)){
		$event= $this->getEvent($data['id']);
		$event->title = $this->getCalendar()->newTitle($data['title']);
		$event->where = array($this->getCalendar()->newWhere($data['location']));
		$event->content = $this->getCalendar()->newContent($data['content']);
		
		// Set the date using RFC 3339 format.
		$startDate 	= $data['startDate'];
		$startTime 	= $data['startTime'];
		$endDate 	= $data['endDate'];
		$endTime 	= $data['endTime'];
		$tzOffset = "-00";
		 
		$when = $this->getCalendar()->newWhen();
		$when->startTime = "{$startDate}T{$startTime}:00.000{$tzOffset}:00";
		$when->endTime = "{$endDate}T{$endTime}:00.000{$tzOffset}:00";
		$event->when = array($when);
		$properties = array('type' => $data['eventType'], 'creator' => $data['creator']);
		$this->addExtendedProperty($event, $properties);
//		$event->save();
		} else {
			throw new Pas_Calendar_Exception('The data supplied is not an array', 500);
		}
	}
}