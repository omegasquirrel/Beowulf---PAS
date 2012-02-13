<?php
/** Controller for manipulating the artefacts data
*
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Database_ArtefactsController extends Pas_Controller_Action_Admin {

    const REDIRECT = '/database/artefacts/';

    protected $_user;
    /**
     * @var array restricted access roles
     */
    protected $_restricted = array('member','public');

    protected $_higherLevel = array('treasure', 'flos', 'admin', 'hero' );

    /**
    * @var array coins pseudonyms
    */
    protected $_coinarray = array(
        'Coin','COIN','coin',
        'token','jetton','coin weight',
        'COIN HOARD', 'TOKEN', 'JETTON'
        );

    /** An array of Roman and Iron Age periods
     * Used for coins
    * @var array Romanic periods
    */
    protected $_periodRomIA = array(
	'Roman','ROMAN','roman',
	'Iron Age','Iron age','IRON AGE',
	'Byzantine','BYZANTINE','Greek and Roman Provincial',
	'GREEK AND ROMAN PROVINCIAL','Unknown',
	'UNKNOWN');
    /** An array of Roman and Prehistoric periods
     * Used for objects
     * @var array
     */
    protected $_periodRomPrehist = array(
	'Roman', 'ROMAN', 'roman',
	'Iron Age', 'Iron age', 'IRON AGE',
	'Byzantine', 'BYZANTINE', 'Greek and Roman Provincial',
	'GREEK AND ROMAN PROVINCIAL', 'Unknown', 'UNKNOWN',
	'Mesolithic', 'MESOLITHIC', 'PREHISTORIC',
	'NEOLITHIC', 'Neolithic', 'Palaeolithic',
	'PALAEOLITHIC', 'Bronze Age', 'BRONZE AGE');

    /** An array of Early medieval periods
     * Used for objects and coins
     * @var array
     */
    protected $_earlyMed = array('Early Medieval','EARLY MEDIEVAL');

    /** An array of Medieval periods
     * Used for coins and objects
     * @var array
     */
    protected $_medieval = array('Medieval','MEDIEVAL');

    /** An array of Post Medieval periods
     * Used for coins and objects
     * @var array
     */
    protected $_postMed = array('Post Medieval','POST MEDIEVAL','Modern', 'MODERN');

    protected $_config, $_finds, $_cs, $_auth;

    /** Setup the contexts by action and the ACL.
     *
     */
    public function init()  {
    $this->_config = $this->_helper->config();
    $this->_helper->_acl->deny('public',array('add','edit'));
    $this->_helper->_acl->allow('public',array(
        'index','record','errorreport',
        'notifyflo'
        ));
    $this->_helper->_acl->allow('member',NULL);
    $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
    $this->_cs = $this->_helper->contextSwitch();
    $this->_cs->setAutoDisableLayout(true)
            ->addContext('csv',array('suffix' => 'csv'))
            ->addContext('kml',array('suffix' => 'kml'))
            ->addContext('rss',array('suffix' => 'rss'))
            ->addContext('atom',array('suffix' => 'atom'))
            ->addContext('rdf',array('suffix' => 'rdf'))
            ->addContext('pdf',array('suffix' => 'pdf'))
            ->addContext('qrcode',array('suffix' => 'qrcode'))
            ->addActionContext('record', array('csv','pdf','qrcode'))
            ->addActionContext('index', array('rss','atom'))
            ->initContext();
    $this->_finds = new Finds();
    $this->_auth = Zend_Registry::get('auth');
    $this->_user = $this->_helper->identity->getPerson();
    }


    private function array_cleanup( $array ) {
    $todelete = array('submit','action','controller','module','csrf');
    foreach( $array as $key => $value ) {
    foreach($todelete as $match){
    if($key == $match){
            unset($array[$key]);
    }
    }
    }
    }
    /** Display a list of objects recorded with pagination
    */
    public function indexAction(){
    $form = new SolrForm();
    $this->view->form = $form;
    $search = new Pas_Solr_Handler('beowulf');
    $search->setFields(array(
    	'id', 'identifier', 'objecttype',
    	'title', 'broadperiod','imagedir',
    	'filename','thumbnail','old_findID',
    	'description', 'county')
    );
    $search->setFacets(array('objectType','county','broadperiod','institution', 'workflow           '));

    if($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())){
    if ($form->isValid($form->getValues())) {
    $params = $form->getValues();
    unset($params['csrf']);
    $this->_helper->Redirector->gotoSimple('index','artefacts','database',$params);
    } else {
    $form->populate($form->getValues());
    $params = $form->getValues();
    }
    } else {

    $params = $this->_getAllParams();
    $form->populate($this->_getAllParams());


    }

    if(!isset($params['q']) || $params['q'] == ''){
        $params['q'] = '*';
    }

    $search->setParams($params);
    $search->execute();

    $this->view->facets = $search->_processFacets();
    $this->view->paginator = $search->_createPagination();
    $this->view->data = $search->_processResults();

    }
    /** Display individual record
     * @todo move comment functionality to a model
    */
    public function recordAction() {
    if($this->_getParam('id',false)) {
    $this->view->recordID = $this->_getParam('id');
    $id = $this->_getParam('id');
    $findsdata = $this->_finds->getIndividualFind($id, $this->getRole());

    if($findsdata) {
    $this->view->finds = $findsdata;
    } else {
        throw new Pas_Exception_NotAuthorised('You are not authorised to view this record');
    }

    $contexts = array(
    'xml','rss','json',
    'atom','kml','georss',
    'ics','rdf','xcs',
    'vcf','csv','pdf');

    if(!in_array($this->_cs->getCurrentContext(), $contexts)) {

    $this->view->findsdata     = $this->_finds->getFindData($id);
    $this->view->findsmaterial = $this->_finds->getFindMaterials($id);
    $this->view->temporals     = $this->_finds->getFindTemporalData($id);
    $this->view->nexts         = $this->_finds->getNextObject($id);
    $this->view->recordsprior  = $this->_finds->getPreviousObject($id);
    $this->view->peoples       = $this->_finds->getPersonalData($id);
    $this->view->findotherrefs = $this->_finds->getFindOtherRefs($id);

    $findspotsdata = new Findspots();
    $this->view->findspots = $findspotsdata->getFindSpotData($id);

    $rallyfind = new Rallies;
    $this->view->rallyfind = $rallyfind->getFindToRallyNames($id);

    $coins = new Coins;
    $this->view->coins = $coins->getCoinData($id);

    $coinrefs = new Coinclassifications();
    $this->view->coinrefs = $coinrefs->getAllClasses($id);

    $thumbs = new Slides;
    $this->view->thumbs = $thumbs->getThumbnails($id);

    $refs = new Publications;
    $this->view->refs = $refs->getReferences($id);

    $comments = new Comments;
    $this->view->comments = $comments->getFindComments($id);

    $response = $this->getResponse();
    if(in_array($this->getRole(),$this->_higherLevel)
            && (!in_array($this->_cs->getCurrentContext(),array(
                'xml','json','qrcode')))){

    $wform = new WorkflowStageForm();
    $wform->id->setValue($id);
    $wform->submit->setLabel('Change workflow');
    $this->view->wform = $wform;
    $response->insert('workflow', $this->view->render('structure/workflow.phtml'));
    } else {
    $findspotsdata = new Findspots();
    $this->view->findspots = $findspotsdata->getFindSpotData($id);
    }
    $form = new CommentFindForm();
    $form->submit->setLabel('Add a new comment');
    $this->view->form = $form;
    if($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())) 	 {
    if ($form->isValid($form->getValues())) {
    $data = $this->_helper->akismet($form->getValues());
    $data['contentID'] = $this->_getParam('id');
    $data['comment_type'] = 'findComment';
    $data['comment_approved'] = 'moderation';
    $comments = new Comments();
    $comments->add($data);
    $this->_flashMessenger->addMessage('Your comment has been entered and will appear shortly!');
    $this->_redirect(self::REDIRECT . 'record/id/' . $this->_getParam('id'));
    $this->_request->setMethod('GET');
    } else {
    $this->_flashMessenger->addMessage('There are problems with your comment submission');
    $form->populate($form->getValues());
    }
    }
    } else {
    $this->_helper->layout->disableLayout();    //disable layout
    $record = $this->_finds->getAllData($id);
    if(in_array($this->_user->role,$this->_restricted)) {
    $record['0']['gridref'] = NULL;
    $record['0']['easting'] = NULL;
    $record['0']['northing'] = NULL;
    $record['0']['lat'] = NULL;
    $record['0']['lon'] = NULL;
    $record['0']['finder'] = NULL;
    $record['0']['address'] = NULL;
    $record['0']['postcode'] = NULL;
    $record['0']['findspotdescription'] = NULL;
    } else {
    $record['0']['gridref'] = NULL;
    $record['0']['easting'] = NULL;
    $record['0']['northing'] = NULL;
    $record['0']['lat'] = NULL;
    $record['0']['lon'] = NULL;
    $record['0']['finder'] = NULL;
    $record['0']['address'] = NULL;
    $record['0']['postcode'] = NULL;
    $record['0']['findspotdescription'] = NULL;
    if(!is_null($record['0']['knownas'])){
    $record['0']['parish'] = NULL;
    $record['0']['fourFigure'] = NULL;
    }
    }
    $this->view->record = $record;
    }
    } else {
        throw new Pas_Exception_Param($this->_missingParameter);
    }
    }

    /** Add an object
     * @todo slim down action, move logic for adding to finds.php model
    */
    public function addAction() {
    $user = $this->getAccount();
    $findID = $this->FindUid();
    $secuid = $this->secuid();
    $fullname = $user->fullname;
    $secure = $user->peopleID;
    if(is_null($secure)){
    $this->_redirect('/error/accountproblem');
    }
    $last = $this->_getParam('copy');
    $this->view->secuid = $secuid;
    $form = new FindForm();
    $form->submit->setLabel('Save record');
    $form->old_findID->setValue($findID);
    $form->secuid->setValue($secuid);
    if(isset($secure)){
    $form->recorderID->setValue($secure);
    $form->recordername->setValue($fullname);
    $form->identifier1ID->setValue($secure);
    $form->idBy->setValue($fullname);
    }
    if(in_array($this->getRole(),$this->_restricted)) {
    $form->removeDisplayGroup('discoverers');
    $form->removeElement('finder');
    $form->removeElement('secondfinder');
    $form->removeElement('idBy');
    $form->recordername->setAttrib('disabled', true);
    $form->removeElement('id2by');
    }
    $this->view->form = $form;
    if($last == 'last') {
    $finddata = $this->_finds->getLastRecord($this->getIdentityForForms());
    foreach($finddata as $finddataflat){
    $form->populate($finddataflat);
    if(isset($secure)){
    $form->recorderID->setValue($secure);
    $form->recordername->setValue($fullname);
    }
    }
    }
    if($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())) 	 {
    if ($form->isValid($form->getValues())) {
    $insertData = $form->getValues();
    $insertData['secuid'] = $secuid;
    $insertData['old_findID'] = $findID;
    $insertData['secwfstage'] = (int)2;
    $insertData['institution'] = $this->getInstitution();
    unset($insertData['recordername']);
    unset($insertData['finder']);
    unset($insertData['idBy']);
    unset($insertData['id2by']);
    unset($insertData['secondfinder']);
    $insert = $this->_finds->add($insertData);
    $this->_helper->solrUpdater->update('beowulf', $insert);
    $this->_redirect(self::REDIRECT . 'record/id/' . $insert);
    $this->_flashMessenger->addMessage('Record created!');
    } else  {
    $this->_flashMessenger->addMessage('Please check and correct errors!');
    $form->populate($form->getValues());
    }
    }
    }
    /** Edit a record
     *
     * @todo move update logic to model finds.php
    */
    public function editAction() {
    if($this->_getParam('id',false)){
    $form = new FindForm();
    $form->submit->setLabel('Update record');
    $this->view->form = $form;
    if(in_array($this->getRole(),$this->_restricted)) {
    $form->removeDisplayGroup('discoverers');
    $form->removeElement('finder');
    $form->removeElement('secondfinder');
    $form->removeElement('idBy');
    $form->recordername->setAttrib('disabled', true);
    $form->removeElement('id2by');
    }
    if($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())) 	 {
    if ($form->isValid($form->getValues())) {
    $updateData = $form->getValues();
    $updateData = array_filter($updateData);
    $id2by = $form->getValue('id2by');
    if($id2by === "" || is_null($id2by)){
    $updateData['identifier2ID'] = NULL;
    }
    unset($updateData['recordername']);
    unset($updateData['finder']);
    unset($updateData['idBy']);
    unset($updateData['id2by']);

    unset($updateData['secondfinder']);
    $oldData = $this->_finds->fetchRow('id=' . $this->_getParam('id'))->toArray();
    $where = array();
    $where[] = $this->_finds->getAdapter()->quoteInto('id = ?', $this->_getParam('id'));
    $this->_finds->update($updateData, $where);
    $solr = new Pas_Solr_Updater();
    $solr->add($this->_getParam('id'),'beowulf');
    $this->_helper->audit($updateData, $oldData, 'FindsAudit',  $this->_getParam('id'),
    	$this->_getParam('id'));
    $this->_helper->solrUpdater->update('beowulf', $this->_getParam('id'));
    $this->_flashMessenger->addMessage('Artefact information updated and audited!');
    $this->_redirect(self::REDIRECT . 'record/id/' . $this->_getParam('id'));
    } else {
    $this->view->find = $this->_finds->fetchRow('id='.$this->_getParam('id'));
    $form->populate($this->_request->getPost());
    }
    } else {
    $id = (int)$this->_request->getParam('id', 0);
    if ($id > 0) {
    $formData = $this->_finds->getEditData($id);
    if(count($formData)){
    $form->populate($formData['0']);
    $this->view->find = $this->_finds->fetchRow('id='.$id);
    } else {
            throw new Pas_Exception_Param($this->_nothingFound);
    }
    }
    }
    } else {
            throw new Pas_Exception_Param($this->_missingParameter);
    }
    }

    /** Delete a record
    */
    public function deleteAction() {
    if ($this->_request->isPost()) {
    $id = (int)$this->_request->getPost('id');
    $del = $this->_request->getPost('del');
    if ($del == 'Yes' && $id > 0) {
    $where = $this->_finds->getAdapter()->quoteInto('id = ?', $id);
    $this->_finds->delete($where);
    $findID = $this->_request->getPost('findID');
    $findspots = new Findspots();
    $whereFindspots = array();
    $whereFindspots[] = $this->_finds->getAdapter()->quoteInto('findID  = ?',
            $findID);
    $this->_flashMessenger->addMessage('Record deleted!');
    $findspots->delete($whereFindspots);
    $this->_helper->solrUpdater->deleteById('beowulf', $id);
    $this->_redirect(self::REDIRECT);
    }
    $this->_flashMessenger->addMessage('No changes made!');
    $this->_redirect('database/artefacts/record/id/' . $id);
    } else {
    $id = (int)$this->_request->getParam('id');
    if ($id > 0) {
    $this->view->find = $this->_finds->fetchRow('id=' . $id);
    }
    }
    }
    /** Enter an error report
     * @todo move insert logic to model
    */
    public function errorreportAction() {
    if($this->_getParam('id',false)) {
    $form = new CommentOnErrorFindForm();
    $form->submit->setLabel('Submit your error report');
    $finds = $this->_finds->getRelevantAdviserFind($this->_getParam('id',0));
    $this->view->form = $form;
    $this->view->finds = $finds;
    if($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())){
    if ($form->isValid($form->getValues())) {
    $data = $form->getValues();
    if ($this->_helper->akismet($data)) {
    $data['comment_approved'] = 'spam';
    }  else  {
    $data['comment_approved'] =  '1';
    }

    $errors = new ErrorReports();
    $this->notify($finds['0']['objecttype'],$finds['0']['broadperiod'],$data);
    $errors->add($data);
    $this->_flashMessenger->addMessage('Your error report has been submitted. Thank you!');
    $this->_redirect(self::REDIRECT.'record/id/' . $this->_getParam('id'));
    } else {
    $form->populate($form->getValues());
    }
    }
    } else {
        throw new Pas_Exception_Param($this->_missingParameter);
    }
    }



    public function notifyfloAction(){
    if($this->_getParam('id',false)) {
    $form = new NotifyFloForm();
    $this->view->form = $form;
    $find = $this->_finds->fetchRow($this->_finds->select()->where('id = ?', $this->_getParam('id')));
    $this->view->find = $find->toArray();
    if($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())){
    if ($form->isValid($form->getValues())) {
    $contacts = new Contacts();
    $to = $contacts->getNameEmail($form->getValue('flo'));
    $cc = $this->_getAdviser($find->objecttype,$find->broadperiod);
    $from[] = array('email' => $this->_user->email, 'name' => $this->_user->fullname);
    $cc = array_merge($cc,$from);
    $assignData = array_merge($find->toArray(),$form->getValues(),$to['0']);
    $this->_helper->mailer($assignData, 'publicFindToFlo', $to, $cc, $from);
    $this->_flashMessenger->addMessage('Your message has been sent');
    $this->_redirect('database/artefacts/record/id/' . $find->id);
    } else {
        $form->populate($form->getValues());
    }
    }
    } else {
        throw new Pas_Exception_Param($this->_missingParameter);
    }
    }



    /** Provide a notification for an object
    */
    protected function notify($objecttype, $broadperiod, $data) {
    $finds = new Users();
    $to = $finds->getOwner($data['comment_findID']);
    $cc = $this->getAdviser($objecttype,$broadperiod);
    $from = array(array(
        'email' => $this->_user->email,
        'name' => $this->_user->fullname));
    $assignData = array_merge($to['0'],$data);
  	$this->_helper->mailer($assignData,'errorSubmission', $to, $cc, $from);

    }
    /** Function to combine an array
    */
    private function _combine($array1,$array2) {
            return array_combine($array1,$array2);
    }
    /** Determine adviser to email
    */
    private function getAdviser($objecttype, $broadperiod) {
    $this->_romancoinsadviser = $this->_config->findsadviser->romancoins;
    $this->_romancoinsadviseremail = $this->_config->findsadviser->romcoins->email;

    $this->_medievalcoinsadviser = $this->_config->findsadviser->medievalcoins;
    $this->_medievalcoinsadviseremail = $this->_config->findsadviser->medcoins->email;

    $this->_romanobjects = $this->_config->findsadviser->romanobjects;
    $this->_romanobjectsemail = $this->_config->findsadviser->romobjects->email;

    $this->_medievalobjects = $this->_config->findsadviser->medievalobjects;
    $this->_medievalobjectsemail = $this->_config->findsadviser->medobjects->email;

    $this->_postmedievalobjects = $this->_config->findsadviser->postmedievalobjects;
    $this->_postmedievalobjectsemail = $this->_config->findsadviser->postmedobjects->email;

    $this->_earlymedievalobjects = $this->_config->findsadviser->earlymedievalobjects;
    $this->_earlymedievalobjectsemail = $this->_config->findsadviser->earlymedobjects->email;

    $this->_catchall = $this->_config->findsadviser->default;
    $this->_catchallemail = $this->_config->findsadviser->def->email;

    switch($objecttype) {
    case (in_array($objecttype,$this->_coinarray) && in_array($broadperiod,$this->_periodRomIA)):
            $adviserdetails = $this->_romancoinsadviser;
            $adviseremail = $this->_romancoinsadviseremail;
            break;
    case (in_array($objecttype,$this->_coinarray) && in_array($broadperiod,$this->_earlyMed)):
            $adviserdetails = $this->_medievalcoinsadviser;
            $adviseremail = $this->_medievalcoinsadviseremail;
            break;
    case (in_array($objecttype,$this->_coinarray) && in_array($broadperiod,$this->_medieval)):
            $adviserdetails = $this->_medievalcoinsadviser;
            $adviseremail = $this->_medievalcoinsadviseremail;
            break;
	case (in_array($objecttype,$this->_coinarray) && in_array($broadperiod,$this->_postMed)):
            $adviserdetails = $this->_medievalcoinsadviser;
            $adviseremail = $this->_medievalcoinsadviseremail;
            break;
    case (!in_array($objecttype,$this->_coinarray) && in_array($broadperiod,$this->_periodRomPrehist)):
            $adviserdetails = $this->_romanobjects;
            $adviseremail = $this->_romanobjectsemail;
            break;
    case (!in_array($objecttype,$this->_coinarray) && in_array($broadperiod,$this->_postMed)):
            $adviserdetails = $this->_postmedievalobjects;
            $adviseremail = $this->_postmedievalobjectsemail;
            break;
    case (!in_array($objecttype,$this->_coinarray) && in_array($broadperiod,$this->_medieval)):
            $adviserdetails = $this->_medievalobjects;
            $adviseremail = $this->_medievalobjectsemail;
            break;
    case (!in_array($objecttype,$this->_coinarray) && in_array($broadperiod,$this->_earlyMed)):
            $adviserdetails = $this->_earlymedievalobjects;
            $adviseremail = $this->_earlymedievalobjectsemail;
            break;
    default:
            $adviserdetails = $this->_catchall;
            $adviseremail = $this->_catchallemail;
            break;
    }

   $people = $this->_combine($adviserdetails->toArray(),$adviseremail->toArray());
   $sendto = array();
   foreach($people as $k => $v){
   $sendto[] = array ('email' => $v, 'name' => $k);
   }
   return $sendto;

   }

}
