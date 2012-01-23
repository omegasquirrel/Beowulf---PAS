<?php
/** Form for publishing comments on finds
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class PublishCommentFindForm extends Pas_Form {

public function __construct($options = null) {

	parent::__construct($options);

	$decorators = array(
            array('ViewHelper'),
    		array('Description', array('tag' => '','placement' => 'append')),
            array('Errors',array('placement' => 'append','tag' => 'li')),
            array('Label', array('separator'=>' ', 'requiredSuffix' => ' *')),
            array('HtmlTag', array('tag' => 'li')),
		    );

	$this->setName('comments');


        $commentType = new Zend_Form_Element_Hidden('comment_type');
        $commentType->addFilters(array('StripTags','StringTrim'))
		->setDecorators(array(
	    array('ViewHelper'),
	    array('Description', array('tag' => '')),
	    array('Errors'),
	    array('HtmlTag', array('tag' => 'p')),
	    array('Label', array('tag' => ''))
	));

	$comment_findID = new Zend_Form_Element_Hidden('contentID');
	$comment_findID->addFilters(array('StripTags','StringTrim'))
		->setDecorators(array(
	    array('ViewHelper'),
	    array('Description', array('tag' => '')),
	    array('Errors'),
	    array('HtmlTag', array('tag' => 'p')),
	    array('Label', array('tag' => ''))
	));

	$comment_author = new Zend_Form_Element_Text('comment_author');
	$comment_author->setLabel('Enter your name: ')
		->setRequired(true)
		->addFilters(array('StripTags','StringTrim'))
		->addValidator('Alnum',false,array('allowWhiteSpace' => true))
		->addErrorMessage('Please enter a valid name!')
		->setDecorators($decorators);

	$comment_author_email = new Zend_Form_Element_Text('comment_author_email');
	$comment_author_email->setLabel('Enter your email address: ')
		->setDecorators($decorators)
		->setRequired(true)
		->addFilters(array('StripTags','StringTrim','StringToLower'))
		->addValidator('EmailAddress',false,array('mx' => true))
		->addErrorMessage('Please enter a valid email address!')
		->setDescription('* This will not be displayed to the public.');

	$comment_author_url = new Zend_Form_Element_Text('comment_author_url');
	$comment_author_url->setLabel('Enter your web address: ')
		->setDecorators($decorators)
		->setRequired(false)
		->addFilters(array('StripTags','StringTrim','StringToLower'))
		->addErrorMessage('Please enter a valid address!')
		->addValidator('Url')
		->setDescription('* Not compulsory');


	$comment_content = new Pas_Form_Element_RTE('comment_content');
	$comment_content->setLabel('Enter your comment: ')
		->setRequired(true)
		->setAttrib('rows',10)
		->setAttrib('cols',40)
		->setAttrib('Height',400)
		->setAttrib('ToolbarSet','Finds')
		->addFilters(array('StringTrim', 'BasicHtml', 'EmptyParagraph', 'WordChars'));

	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setAttrib('id', 'submitbutton')
		->removeDecorator('label')
		->removeDecorator('HtmlTag')
		->removeDecorator('DtDdWrapper')
		->setAttrib('class', 'large');

        $hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_config->form->salt)
		->removeDecorator('DtDdWrapper')
		->removeDecorator('HtmlTag')->removeDecorator('label')
		->setTimeout(4800);
	$this->addElement($hash);

	$status = new Zend_Form_Element_Radio('commentStatus');
	$status->setLabel('Message status:')
		->addMultiOptions(array('isspam' => 'Set as spam',
                    'isham' => 'Submit ham?',
                    'notspam' => 'Spam free'))
		->setValue('notSpam')
		->addFilters(array('StripTags','StringTrim','StringToLower'))
		->setOptions(array('separator' => ''))
		->setDecorators($decorators);

       $commentApproval = new Zend_Form_Element_Radio('comment_approved');
       $commentApproval->setLabel('Approval:')
		->addMultiOptions(array('moderation' => 'Moderation','approved' => 'Approved'))
		->setValue('approved')
		->addFilters(array('StripTags','StringTrim','StringToLower'))
		->setOptions(array('separator' => ''))
		->setDecorators($decorators);

	$this->addElements(array(
	$comment_author, $comment_author_email, $comment_content,
        $comment_author_url, $comment_findID, $commentApproval,
        $commentType, $status, $hash, $submit)
	);

	$this->addDisplayGroup(array(
	'comment_author','comment_author_email','comment_author_url',
	'comment_content', 'commentStatus', 'comment_approved',
        'contentID', 'comment_type'),
        'details');

	$this->details->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
	$this->details->removeDecorator('HtmlTag');
	$this->details->removeDecorator('DtDdWrapper');
	$this->details->setLegend('Enter your comments: ');

	$this->addDisplayGroup(array('submit'), 'submit');
	}
}