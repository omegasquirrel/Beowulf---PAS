<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SpatialNameSearch
 *
 * @author danielpett
 */
class Pas_Edina_SpatialNameSearch extends Pas_Edina {

    const METHOD = 'spatialNameSearch?';

    const CORNERS = 4;

    protected $_operators = array('within','intersect');

    protected $_name;

    protected $_minx;

    protected $_maxx;

    protected $_miny;

    protected $_maxy;

    public function setName(array $names){
        if(!is_array($names)){
            throw new Pas_Param_Exception('The list of names must be an array');
        } else {
            return $this->_name = implode(',',$names);
        }
    }


    protected $_operator = 'within';

    public function setOperator($operator){
        if(!in_array($operator, $this->_operators)){
            throw new Pas_Edina_Exception('The operator you provided is not in scope');
        } else {
            $this->_operator = $operator;
        }
    }

    public function setBoundingBox( array $bbox){
       if(is_array($bbox)){
           $this->_bboxCheck($bbox);
       }
       $this->_minx = $bbox['0'];
       $this->_miny = $bbox['1'];
       $this->_maxx = $bbox['2'];
       $this->_maxy = $bbox['3'];
    }

    /** Check that bounding box coordinates are valid
     * @access protected
     * @param array $bbox
     * @throws Pas_Edina_Exception
     */
    protected function _bboxCheck($bbox){
      if(count($bbox) === self::CORNERS){
      //Validate the points
      foreach($bbox as $corner){
          if(!is_numeric($corner)){
              throw new Pas_Edina_Exception('Coordinate provided not numeric');
          } elseif((abs($corner) > 180)){
              throw new Pas_Edina_Exception('Coordinate greater than 180 &deg;');
          }
      }
      //Check mathematics
      if($bbox['0']  > $bbox['2']){
        //This checks that the minimum latitude is smaller than maximum
        //latitude, if not throw exception
        throw new Pas_Edina_Exception('Minimum latitude greater than maximum');
       }

      if($bbox['1'] > $bbox['3'] ){
        //This checks that the minimum latitude is smaller than maximum
        ////latitude, if not throw exception
        throw new Pas_Edina_Exception('Minimum longitude greater than maximum');
      }
    }
    }

    public function get() {
        $params = array(
            'name' => $this->_name,
            'minx' => $this->_minx,
            'miny' => $this->_miny,
            'maxx' => $this->_maxx,
            'maxy' => $this->_maxy,
            'operator' => $this->_operator
        );

        parent::get(self::METHOD, $params);
    }
}