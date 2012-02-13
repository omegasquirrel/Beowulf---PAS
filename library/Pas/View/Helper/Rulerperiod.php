<?php

class Pas_View_Helper_Rulerperiod extends Zend_View_Helper_Abstract
{
   
   function rulerperiod($string)
{
    $result = strtolower($string);

    $result = preg_replace("/[^a-z0-9\s-]/", "", $result);
    $result = trim(preg_replace("/\s+/", " ", $result));
    $result = trim(substr($result, 0, 45));
    $result = preg_replace("/\s/", "", $result);

    return $result;
}
}