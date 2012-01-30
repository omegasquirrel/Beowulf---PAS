<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of FacetSearchHandler
 *
 * @author danielpett
 */
class Pas_Solr_FacetSearchHandler {
    //put your code here

    public function buildQuery(array $facets){
        if(is_array($facets)){


        } else {
            throw new Pas_Solr_Exception('Facet queries must be an array');
        }

    }

    protected function createSyntax(array $facet){
        $queries = array();

        foreach$facet as $k => $v{
                $this->_query->createFacetMultiQuery($k);


        }
        return $queries;
    }
}

?>
