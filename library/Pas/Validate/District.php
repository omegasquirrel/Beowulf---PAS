<?php
/**
 * A validation class for checking for unique usernames
 * @category   Pas
 * @package    Zend_Validate
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_Validate_Abstract
 */
class Pas_Validate_UsernameUnique extends Zend_Validate_Abstract {
	const NOT_VALID = 'notValid';

	/**
	* Validation failure message template definitions
	*
	* @var array
	*/
	protected $_messageTemplates = array(
	self::NOT_VALID => 'That district does not exist.',
	);




	public function isValid($value){
	$value = (string) $value;
	$
	if(!in_array($letterpair,$this->letters)) {
	$this->_error(self::NOT_VALID);
	return false;
	}
	return true;
	}
}