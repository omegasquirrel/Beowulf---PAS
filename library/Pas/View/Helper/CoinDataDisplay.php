<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CoinDataDisplay
 *
 * @author danielpett
 */
class Pas_View_Helper_CoinDataDisplay extends Zend_View_Helper_Abstract {

    protected $_numismatics = array('COIN', 'MEDALLION');

    protected $_objects = array('JETTON', 'TOKEN');

    protected $_broadperiods = array('IRON AGE', 'ROMAN', 'BYZANTINE',
        'EARLY MEDIEVAL', 'GREEK AND ROMAN PROVINCIAL', 'MEDIEVAL', 'POST MEDIEVAL',
        'MODERN', 'UNKNOWN');

    public function coinDataDisplay($objectType, $broadperiod, $coins, $finds){
		$types = array_merge($this->_numismatics, $this->_objects);
        if(in_array(strtoupper($objectType), $types)){
		if(sizeof($coins)>0){
        if(in_array(strtoupper($broadperiod), $this->_broadperiods)){

            if(in_array(strtoupper($objectType), $this->_numismatics)){
                $template = str_replace(' ','', $broadperiod);
                $html = $this->view->partialLoop('partials/database/' . strtolower($template) . 'Data.phtml', $coins);
            } elseif(in_array(strtoupper($objectType), $this->_objects)){
                $html = $this->view->partialLoop('partials/database/jettonData.phtml', $coins);
            } else {
            	return false;
            }


        } else {
            throw new Pas_Exception_BadJuJu('You cannot have a coin of that period');
        }
        } else {
            $html = '<div>';
            $html .= '<h4>Numismatic data</h4>';
            $html .= '<p>No numismatic data has been recorded for this coin yet.</p>';
            $html .= '<div class="noprint">';
            if(in_array(strtoupper($objectType), $this->_numismatics)){
            $html .= $this->view->addCoinLink(
                    $finds[0]['old_findID'],
                    $finds[0]['id'],
                    $finds[0]['secuid'],
                    $finds[0]['createdBy'],
                    $finds[0]['broadperiod']);
            $html .= '</div></div>';
            } elseif(in_array(strtoupper($objectType), $this->_objects)){
            $html .= $this->view->addJettonLink(
                    $finds[0]['old_findID'],
                    $finds[0]['id'],
                    $finds[0]['secuid'],
                    $finds[0]['createdBy'],
                    $finds[0]['broadperiod']);
            $html .= '</div></div>';	
            }

        }
        }
        return $html;
    }

    }


