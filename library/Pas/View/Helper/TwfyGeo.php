<?php
/**
 * A view helper for retrieving the geographic boundaries of a parliamentary constituency
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_View_Helper_Abstract
 * @see  http://www.theyworkforyou.com/ for documentation
 */
class Pas_View_Helper_TwfyGeo extends Zend_View_Helper_Abstract {


	/** Get the boundary details for the constituency provided
	 *
	 * @param string $constituency
	 */
	public function TwfyGeo($data) {
	$geo = new Pas_Twfy_Geometry;

        $geo = $geo->get($data);
        Zend_Debug::dump($geo);
        exit;
        return $this->buildMap($geo, $data);
        }

        public function buildMap($geo, $data){
        $html =  $this->view->partial('partials/news/map.phtml', $geo);
        $html .= $this->osDataToConst($geo->name);
        $html .= $this->SmrDataToConst($geo->name);
        $html .= $this->findsOfNoteConst($geo->name);
        $html .= $this->mpbio($data->full_name);
        $html .= $this->politicalhouse($data->house);
        $html .= '<div id="career">';
        $html .= $this->partialLoop('partials/news/mp.phtml',$this->data);
        $html .= '</div>';
        return $html;
        }
}