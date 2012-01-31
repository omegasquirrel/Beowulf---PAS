<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/** This view helper takes the array of facets and their counts and produces
 * an html rendering of these with links for the search.
 * @category Pas
 * @package Pas_View
 * @subpackage Helper
 * @version 1
 * @since 30/1/2012
 * @copyright Daniel Pett
 * @author Daniel Pett
 * @license GNU
 * @uses Pas_Exception_BadJuJu
 * @uses Zend_View_Helper_Url
 * @uses Zend_Controller_Front
 */
class Pas_View_Helper_FacetCreator extends Zend_View_Helper_Abstract {

    /** Create the facets boxes for rendering
     * @access public
     * @param array $facets
     * @return string
     * @throws Pas_Exception_BadJuJu
     */

    public function facetCreator(array $facets){
        if(is_array($facets)){
        $html = '<div id="facets">';
        $html .= '<h3>Search facets</h3>';
        foreach($facets as $facetName => $facet){
            $html .= $this->_processFacet($facet, $facetName);
        }
        $html .= '</div>';
        return $html;
        } else {
            throw new Pas_Exception_BadJuJu('The facets sent are not an array');
        }
    }

    /** Process the facet array and name
     * @access public
     * @param array $facet
     * @param string $facetName
     * @return string
     * @throws Pas_Exception_BadJuJu
     * @uses Zend_Controller_Front
     * @uses Zend_View_Helper_Url
     */
    protected function _processFacet(array $facet, $facetName){
        if(is_array($facet)){
        $html = '<div id="facet-' . $facetName .'" class="thumbnail">';
        $html .= '<h4>' . $this->_prettyName($facetName) . '</h4>';
        $html .= '<ul>';
        $facet = array_slice($facet,0,10);

        foreach($facet as $key => $value){
        $url = $this->view->url(array('fq' . $facetName => $key),'default',false);
        $html .= '<li>';
        $html .= '<a href="' . $url . '" title="Facet query for ' . $key;
        $html .= '">';
        $html .= $key . ' ('. number_format($value) .')';
        $html .= '</a>';
        $html .= '</li>';
        }

        $html .= '</ul>';
        $request = Zend_Controller_Front::getInstance()->getRequest()->getParams();

        if(isset($request['page'])){
            unset($request['page']);
        }

        $facet = $request['fq' . $facetName];
        if(isset($facet)){
            unset($request['fq' . $facetName]);
            $html .= '<p><a href="' . $this->view->url(($request),'default',true)
                    . '" title="Clear the facet">Clear this facet</a></p>';
        }

        $html .= '</div>';
        return $html;
        } else {
            throw new Pas_Exception_BadJuJu('The facet is not an array');
        }
    }

    /** Create a pretty name for the facet
     * @access public
     * @param string $name
     * @return string
     */
    protected function _prettyName($name){
        switch($name){
            case 'objectType':
                $clean = 'Object type';
                break;
            case 'broadperiod':
                $clean = 'Broad period';
                break;
            case 'county':
                $clean = 'County of origin';
                break;
            default:
                $clean = ucfirst($name);
                break;
        }
        return $clean;
    }
}