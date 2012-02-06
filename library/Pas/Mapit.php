<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/** A series of method calls to the excellent mysociety produced MapIt api.
 * @category Pas
 * @package Pas_MapIt
 * @since 6/2/12
 * @version 1
 * @copyright Daniel Pett, British Museum
 * @license GNU
 * @author Daniel Pett
 * @see http://mapit.mysociety.org/ for full documentation of api
 */
class Pas_Mapit {

    /** The base mapit url
     *
     */
    const MAP_IT_URI = 'http://mapit.mysociety.org/';


    /** Set up the cache
        *
        * @var type
        */
    protected $_cache;

    /** Default formats to check calls in
     *
     * @var type
     */
    protected $_allFormats = array('json', 'html');

    /** Generally default to json for response
     *
     * @var type
     */
    protected $_format = 'json';

    /** Construct the cache. If an api key is needed, this can be set here
     * when it is introduced
     */
    public function __construct(){
    $frontendOptions = array(
        'lifetime' => 31556926,
        'automatic_serialization' => true
        );
    $backendOptions = array('cache_dir' => 'app/cache/mapit');
    $this->_cache = Zend_Cache::factory(
            'Output',
            'File',
            $frontendOptions,
            $backendOptions)
            ;
    }

    /** Perform a curl request based on url provided
    * @access public
    * @uses Zend_Cache
    * @uses Zend_Json_Decoder
    * @param string $method
    * @param array $params
    */
    public function get($method, array $params) {
    $url = $this->createUrl($method,$params);
    if (!($this->_cache->test(md5($url)))) {
    $config = array(
    'adapter'   => 'Zend_Http_Client_Adapter_Curl',
    'curloptions' => array(
    CURLOPT_POST =>  true,
    CURLOPT_USERAGENT =>  $_SERVER["HTTP_USER_AGENT"],
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_LOW_SPEED_TIME => 1,
    ),
    );

    $client = new Zend_Http_Client($url, $config);
    $response = $client->request();

    $data = $response->getBody();
    $this->_cache->save($data);
    } else {
    $data = $this->_cache->load(md5($url));
    }
    if($this->_format === 'json'){
    return Zend_Json_Decoder::decode($data, Zend_Json::TYPE_OBJECT);
    } else {
        return $data;
    }
    }

    /** Build a url string
        * @param string $method The method to use
        * @param array $params
        */
    public function createUrl(array $params){
        if(is_array($params)){
        return self::MAP_IT_URI . http_build_query($params, null, '/');
        } else {
            throw new Pas_Twfy_Exception('Parameters have to be an array',500);
        }
    }

}
