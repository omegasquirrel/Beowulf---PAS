<?php
class Pas_Pdf_Table_Column extends Pas_Pdf_Table_Cell {

	private $_colspan=1;

	public function setColspan($value){
		$this->_colspan=$value;
	}

	public function getColspan(){
		return $this->_colspan;
	}
}

?>