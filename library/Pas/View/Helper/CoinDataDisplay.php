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

    protected $_numismatics = array('COIN');

    protected $_objects = array('JETTON', 'TOKEN');

    protected $_broadperiods = array('IRON AGE', 'ROMAN', 'BYZANTINE',
        'EARLY MEDIEVAL', 'GREEK AND ROMAN PROVINCIAL', 'MEDIEVAL', 'POST MEDIEVAL',
        'MODERN');

    public function coinDataDisplay($objectType, $broadperiod, $coins, $finds){
		$types = array_merge($this->_numismatics, $this->_objects);
        if(in_array(strtoupper($objectType), $types)){
        	if(sizeof($coins)>0){

        if(in_array(strtoupper($broadperiod), $this->_broadperiods)){

            if(in_array(strtoupper($objectType), $this->_numismatics)){
                $template = str_replace(' ','', $broadperiod);
                $html = $this->view->partialLoop('partials/database/' . strtolower($template) . 'Data.phtml', $coins);
            } elseif(in_array(strtoupper($objectType), $this->_numismatics)){
                $html = $this->view->partialLoop('partials/database/jettonData.phtml', $coins);
            }


        } else {
            throw new Pas_Exception_BadJuJu('You cannot have a coin of that period');
        }

       


        } else {
            $html = '<div>';
            $html .= '<h3>Numismatic data</h3>';
            $html .= '<p>No numismatic data has been recorded for this coin yet.</p>';
            $html .= '<div class="noprint">';
            $html .= $this->view->addCoinLink(
                    $finds['old_findID'],
                    $finds['id'],
                    $finds['secuid'],
                    $finds['createdBy'],
                    $finds['broadperiod']);
            $html .= '</div>';

        }
        }
        return $html;
    }

    }


