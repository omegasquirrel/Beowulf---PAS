<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of BoundingBoxCheck
 *
 * @author danielpett
 */
class Pas_Solr_BoundingBoxCheck {

    const CORNERS = 4;

    protected $_coordString;

    public function __construct($string) {
        $this->_coordString = $string;
    }

    /** Check the comma delimited string for validity
     * @access public
     * @param string $string
     * @throws Pas_Solr_Exception
     */
    public function checkCoordinates(){
      if(!is_null($this->_coordString)){
          return $this->_createArray($this->_coordString);
      } else {
          throw new Pas_Solr_Exception('No corner coordinates provided');
      }

    }

    /** Create the array for searching solr bounding box
     *
     * @param string $string
     * @return array $corner 4 key values for searching
     * @throws Pas_Solr_Exception
     */
    protected function _createArray(){
      $bbox = explode(',',$this->_coordString);
      if(count($bbox) === self::CORNERS){
      foreach($bbox as $corner){

          if(!is_numeric($corner)){
              throw new Pas_Solr_Exception('Coordinate provided not numeric');
          } elseif((abs($corner) > 180)){
              throw new Pas_Solr_Exception('Coordinate greater than 180 &deg;');
          }
      }
      if($bbox['0']  > $bbox['2']){
        //This checks that the minimum latitude is smaller than maximum
        //latitude, if not throw exception
        throw new Pas_Solr_Exception('Minimum latitude greater than maximum');
       }

      if($bbox['1'] > $bbox['3'] ){
        //This checks that the minimum latitude is smaller than maximum
        ////latitude, if not throw exception
        throw new Pas_Solr_Exception('Minimum longitude greater than maximum');
      }

      //$bbox elements - 0 = minLat, 1 = minLon, 2 = maxLat, 3 = maxLon
      $solrQuery = 'coordinates:[' . $bbox['0'] . ',' .  $bbox['1'] . ' TO '
              . $bbox['2'] . ',' . $bbox['3'] . ']';
      return $solrQuery;
      } else {
          throw new Pas_Solr_Exception('Invalid count of corners');
      }
}


}

?>
