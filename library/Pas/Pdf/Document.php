<?php
class Pas_Pdf_Document extends Pas_Pdf{

	/*
	 * Margin (margin-top,margin-right,margin-bottom,margin-left)
	 */
	private $_margin=array(30,20,30,20);
	private $_headerYOffset=10;	//y offset from page top
	private $_footerYOffset=10; //y offset from margin-bottom --> page bottom
	private $_header;
	private $_footer;
	private $_filename="document.pdf";
	private $_path="/";

	/**
	 * Set Document Margin
	 *
	 * @param integer $value
	 * @param Pas_Pdf $position
	 */
	public function setMargin($position,$value){
		$this->_margin[$position]=$value;
	}

	/**
	 * Get Document Margins
	 *
	 * @return array(TOP,RIGHT,BOTTOM,LEFT)
	 */
	public function getMargins(){
		return $this->_margin;
	}

	/**
	 * Set Footer
	 *
	 * @param Pas_Pdf_Table $table
	 */
	public function setFooter(Pas_Pdf_Table $table){
		$this->_footer=$table;
	}

	/**
	 * Set Header
	 *
	 * @param Pas_Pdf_Table $table
	 */
	public function setHeader(Pas_Pdf_Table $table){
		$this->_header=$table;
	}

	public function __construct($filename,$path){
		$this->_filename=$filename;
		$this->_path=$path;
		parent::__construct();
	}

	/**
	 * Create a new Page for this Document
	 * Sets all default values (margins,...)
	 * @param mixed $param
	 * @return Pas_Pdf_Page
	 */
	public function createPage($param=Zend_Pdf_Page::SIZE_A4){
		$page=new Pas_Pdf_Page($param);
		$page->setMargins($this->_margin);
		return $page;
	}

	/**
	 * Add Page to this Document
	 *
	 * @param Pas_Pdf_Page $page
	 */
	public function addPage(Pas_Pdf_Page $page){
		//add debug page
		//$page->setMargins($this->_margin);
		//$this->_debugTable($page);

		//add pages with new pages (page breaks)
		if($pages=$page->getPages()){
			foreach ($pages as $p){
				$p->setMargins($this->_margin);
				$this->pages[]=$p;
			}
		}
		else{
			$page->setMargins($this->_margin);
			$this->pages[]=$page;
		}
	}

	/**
	 * (renders) and Saves the Document to the specified File
	 *
	 */
	public function save(){
		//add header/footer to each page
		$i=1;
		foreach ($this->pages as $page) {
			$this->_drawFooter($page,$i);
			$this->_drawHeader($page,$i);
			$i++;
		}

		parent::save("{$this->_path}/{$this->_filename}");
	}

	private function _drawFooter(Pas_Pdf_Page $page,$currentPage){
		if(!$this->_footer) return;
		if ($page instanceof Pas_Pdf_Page) {

			//set table width
			$currFooter = clone $this->_footer;
			//check for special place holders
			$rows=$currFooter->getRows();
			foreach ($rows as $key=>$row) {
				$row->setWidth($page->getWidth()-$this->_margin[Pas_Pdf::LEFT] - $this->_margin[Pas_Pdf::RIGHT]);
				$cols=$row->getColumns();
				$num=0;
				foreach ($cols as $col) {
					if($col->hasText()){
						$num+=$col->replaceText('@@CURRENT_PAGE',$currentPage);
						$num+=$col->replaceText('@@TOTAL_PAGES',count($this->pages));
					}
				}

				if($num>0){
					$row->setColumns($cols);
					$currFooter->replaceRow($row,$key);
				}

			}

			//add table
			$page->addTable($currFooter,
					$this->_margin[Pas_Pdf::LEFT],
					($page->getHeight()-$this->_margin[Pas_Pdf::BOTTOM]-$this->_margin[Pas_Pdf::TOP]+$this->_footerYOffset),
					false
					);
		}

	}

	private function _drawHeader(Pas_Pdf_Page $page,$currentPage){
		if(!$this->_header) return;

		if ($page instanceof Pas_Pdf_Page) {

			//set table width
			$currHeader = clone $this->_header;

			//check for special place holders
			$rows=$currHeader->getRows();
			foreach ($rows as $key=>$row) {
				$row->setWidth($page->getWidth()-$this->_margin[Pas_Pdf::LEFT] - $this->_margin[Pas_Pdf::RIGHT]);
				$cols=$row->getColumns();
				$num=0;
				foreach ($cols as $col) {
					if($col->hasText()){
						$num+=$col->replaceText('@@CURRENT_PAGE',$currentPage);
						$num+=$col->replaceText('@@TOTAL_PAGES',count($this->pages));
					}
				}

				if($num>0){
					$row->setColumns($cols);
					$currHeader->replaceRow($row,$key);
				}

			}


			$page->addTable($currHeader,
					$this->_margin[Pas_Pdf::LEFT],
					+$this->_headerYOffset-$this->_margin[Pas_Pdf::TOP],
					false
					);
		}
	}

	private function _debugTable(Pas_Pdf_Page $page){

		$style1=new Zend_Pdf_Style();
		$style1->setLineColor(new Zend_Pdf_Color_Html("blue"));
		$style1->setLineWidth(0.5);

		$style2=new Zend_Pdf_Style();
		$style2->setLineColor(new Zend_Pdf_Color_Html("red"));
		$style2->setLineWidth(0.5);

		$style3=new Zend_Pdf_Style();
		$style3->setLineColor(new Zend_Pdf_Color_Html("black"));
		$style3->setLineWidth(0.5);

		$table=new Pas_Pdf_Table(5);

		$header=new Pas_Pdf_Table_HeaderRow(array('H1','H2','H3','H4','H5'));

		$table->addHeader($header);

		$row=new Pas_Pdf_Table_Row();
		$row->setBorder(Pas_Pdf::BOTTOM,$style1);
		$font = Zend_Pdf_Font::fontWithName ( ZEND_Pdf_Font::FONT_HELVETICA);
		$row->setFont($font,12);

		$col=new Pas_Pdf_Table_Column();
		$col->setText("test1");
		$col->setBorder(Pas_Pdf::RIGHT,$style2);
		$col->setBorder(Pas_Pdf::LEFT,$style2);
		$cols[]=$col;

		$col=new Pas_Pdf_Table_Column();
		$col->setText("test2");
		$col->setBorder(Pas_Pdf::RIGHT,$style2);
		$col->setBorder(Pas_Pdf::LEFT,$style2);
		$cols[]=$col;

		$col=new Pas_Pdf_Table_Column();
		$col->setText("test3");
		$col->setBorder(Pas_Pdf::RIGHT,$style2);
		$col->setBorder(Pas_Pdf::LEFT,$style2);
		$cols[]=$col;

		$col=new Pas_Pdf_Table_Column();
		$col->setText("test4");
		$col->setBorder(Pas_Pdf::RIGHT,$style2);
		$col->setBorder(Pas_Pdf::LEFT,$style2);
		$cols[]=$col;

		$col=new Pas_Pdf_Table_Column();
		$col->setText("test5");
		$col->setBorder(Pas_Pdf::RIGHT,$style2);
		$col->setBorder(Pas_Pdf::LEFT,$style2);
		$cols[]=$col;

		/*
		$col=new Pas_Pdf_Table_Column();
		$col->setText("test0");
		$col->setBorder(Pas_Pdf::LEFT,$style2);
		$col->setBorder(Pas_Pdf::RIGHT,$style2);
		$cols[]=$col;


		$col=new Pas_Pdf_Table_Column();
		$col->setText("test00",Pas_Pdf::CENTER,Pas_Pdf::BOTTOM);
		$col->setBorder(Pas_Pdf::RIGHT,$style3);
		$col->setBackgroundColor(new Zend_Pdf_Color_Html("red"));
		$cols[]=$col;

		$col=new Pas_Pdf_Table_Column();
		$col->setImage(Zend_Registry::get("root_dir")."/html/images/pamela_logo.png",Pas_Pdf::CENTER, Pas_Pdf::TOP,0.8);
		$col->setBackgroundColor(new Zend_Pdf_Color_Html("gray"));
		$col->setBorder(Pas_Pdf::RIGHT,$style3);
		$cols[]=$col;



		$col=new Pas_Pdf_Table_Column();
		$col->addText("test1 test2 test3 test4 test5 test6 test7 test8 test9 test10",Pas_Pdf::RIGHT);
		$col->setBorder(Pas_Pdf::RIGHT,$style2);
		$font = Zend_Pdf_Font::fontWithName ( ZEND_Pdf_Font::FONT_COURIER_BOLD);
		$col->setFont ( $font, 30 );

		$cols[]=$col;

		$col=new Pas_Pdf_Table_Column();
		$col->addText("test33",Pas_Pdf::CENTER,Pas_Pdf::MIDDLE);
		$col->setWidth(60);
		$col->setBorder(Pas_Pdf::RIGHT,$style2);
		$col->setBackgroundColor(new Zend_Pdf_Color_Html("green"));
		$col->setColor(new Zend_Pdf_Color_Html("white"));
		$cols[]=$col;
		*/

		$row->setColumns($cols);

		for($i=0;$i<100;$i++){
			$table->addRow($row);
		}

		$page->addTable($table,0,0);
	}
}

?>