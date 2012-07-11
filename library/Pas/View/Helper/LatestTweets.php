<?php
/**
 * This class is to retrieve tweets and display them.
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @uses Zend_View_Helper_Abstract
 * @uses Pas_View_Helper_AutoLink
 * @uses Zend_View_Helper_Url
 * @author Daniel Pett
 * @since September 13 2011
*/
class Pas_View_Helper_LatestTweets
	extends Zend_View_Helper_Abstract {

	protected $_cache;

	protected $_config;

	/** Constructor
	 *
	 */
	public function __construct(){
	$this->_cache = Zend_Registry::get('cache');
	$this->_config = Zend_Registry::get('config');
	}



	/** Call Twitter after getting token for oauth
	 *
	 */
	public function callTwitter() {
        if (!($this->_cache->test('findsorguk'))) {
	$tokens = new OauthTokens();
	$token = $tokens->fetchRow($tokens->select()->where('service = ?', 'twitterAccess'));

	$twitter = new Zend_Service_Twitter(array('username' => 'findsorguk','accessToken' => unserialize($token->accessToken)));
	$tweets = $twitter->status->userTimeline(array('id' => 'findsorguk', 'count' => 3));
        $twits = array();
        foreach($tweets as $xml){
            $twits[] = $xml->asXml();
        }
        $this->_cache->save($twits);
	} else {
	$twits = $this->_cache->load('findsorguk');
	}

	return $this->buildHtml($twits);
	}

	/** Build the html
	 *
	 * @param array $response
	 */
	public function buildHtml($twits){
	$html = '';
	$html .= '<ul>';
	foreach($twits as $post){
            $xml = new SimpleXMLElement($post);
	$html .= '<li>On <strong>'. date('m.d.y @ H:m:s',strtotime($xml->created_at))
	. '</strong>, <strong><a href="http://www.twitter.com/'. $xml->user->screen_name
	. '">' . $xml->user->screen_name . '</a></strong> said: '. $this->view->autoLink($xml->text)
	. '</li>';
	}
	$html .= '</ul>';
	return $html;
	}

	/** Call Twitter to get tweets
	 *
	 */
	public function latestTweets() {
	return $this->callTwitter();
	}


}

