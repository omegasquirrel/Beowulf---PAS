<?php
/** Data model for accessing data for array based searching
* @category 	Pas
* @package 		Pas_Db_Table
* @subpackage 	Abstract
* @author 		Daniel Pett dpett @ britishmuseum.org
* @copyright 	2010 - DEJ Pett
* @license 		GNU General Public License
* @version 		1
* @since 		22 October 2010, 17:12:34
* @todo 		this is to be replaced by SOLR and sucks the big dong
*/
class Search extends Pas_Db_Table_Abstract {

	protected $_name = 'finds';

	protected $_primary = 'id';

	protected $_higherlevel = array('admin','flos','fa');

	protected $_research = array('hero','research');

	protected $_restricted = array('public','member');

	protected $_edittest = array('flos','member');



	/** Get user's role for checking permissions
	* @return string $role
	*/
	public function getRole() {
	if($this->_auth->hasIdentity()) {
	$user = $this->_auth->getIdentity();
	$role = $user->role;
	return $role;
	} else {
	$role = 'public';
	return $role;
	}
	}

	/** Get user's institution
	* @return string $institution
	*/
	protected function getInstitution() {
	if($this->_auth->hasIdentity()) {
	$user = $this->_auth->getIdentity();
	$institution = $user->institution;
	return $institution;
	}
	}

	public function goGetTheHero($params, $role) {

	$sql = 'SELECT finds.secuid AS SecUID, finds.old_findID AS FindID, finds.objecttype AS ObjectType, cert1.term AS ObjectTypeCertainty, finds.description AS ObjectDescription, finds.classification AS ObjectClassification, finds.subclass AS ObjectSubClassification, finds.inscription AS ObjectInscription, finds.notes AS Notes, cert2.term AS ObjectDate1Certainty, numdate1 AS DateFrom, period1.term AS PeriodFrom, dq1.term AS CalendarDate1Qualifier, cert3.term AS ObjectDate2Certainty, period2.term AS PeriodTo, dq2.term AS CalendarDate2Qualifier, finds.numdate2 AS DateTo, period3.term AS AscribedCulture, mat1.term AS PrimaryMaterial, mat2.term AS AdditionalMaterial, manufactures.term AS MethodOfManufacture, surfaces.term AS SurfaceTreatment, finds.length, finds.width, finds.thickness, finds.diameter, finds.weight, finds.quantity, wear.term AS Wear, p.term AS Preservation, c.term AS Completeness, reuse AS EvidenceOfReuse, findspots.gridref AS OSRef, findspots.easting AS Easting, findspots.northing AS Northing, people.fullname AS Finder, dq3.term AS DateFound1Qualifier, finds.datefound1 AS DateFound1, dq4.term AS DateFound2Qualifier, datefound2 AS DateFound2, disco.method AS MethodsOfDiscovery, finds.disccircum AS CircumstancesofDiscovery, pep2.fullname AS RecordedBy, pep3.fullname AS PrimaryIdentifier, pep4.fullname AS SecondaryIdentifier, finds.curr_loc AS CurrentLocation, finds.musaccno AS MuseumAccNo, sub.action AS SubsequentAction, finds.other_ref AS OtherReference,
sp.term AS SubperiodFrom,  sp2.term AS SubperiodTo, period4.term AS PeriodOfReuse, dm.term AS DecmethodObsolete, ds.term AS Decstyle, finds.findofnote AS CoolFind, findspots. old_findspotid AS FindspotCode, finds.old_finderID  AS FormerFinderID, finds.old_candidate AS FormerCandidateTerm, finds.smrrefno AS FormerPhotoReference, finds.smrrefno AS FormerDrawingReference, finds.smrrefno AS ExportedToWeb, finds. smr_ref AS SMRReference, finds.broadperiod AS BroadPeriod, finds.secwfstage as WorkflowStage,finds.createdBy AS FindOfficer, findspots.county AS county, findspots.district AS district, findspots.parish as parish, findspots.address, findspots.postcode, findspots.description, findspots.knownas AS KnownAs, findspots.comments, pep5.fullname AS LandOwner, smrrefno AS Occupier, lu1.term AS SpecificLanduse,lu2.term AS GeneralLanduse, finds.id AS IDOfFind, findspots.createdBy AS FindOfficerFindspot, rulers.issuer AS Ruler, cert4.term AS RulerQualifier, denoms.denomination AS Denomination,cert6.term AS DenominationQualifier, mints.mint_name AS Mint, cert5.term AS MintQualifier,
smrrefno AS CoinType, sta.term AS STATUS, cert8.term AS StatusQualifier, coins.moneyer as Moneyer, coins.obverse_description AS Obverse_description, coins.obverse_inscription AS Obverse_inscription, coins.initial_mark AS Initial_mark, coins.reverse_description AS Reverse_description,coins.reverse_inscription AS Reverse_inscription, coins.reverse_mintmark AS Reverse_mintmark, coins.degree_of_wear AS Degree_of_wear, coins.die_axis_measurement AS Die_axis_measurement,  cert7.term AS Die_axis_certainty, coins.reeceID';
//JOIN tables
	$sql .= '
	FROM finds
	LEFT JOIN findspots ON finds.secuid = findspots.findID
	LEFT JOIN coins ON finds.secuid = coins.findID
	LEFT JOIN certaintytypes AS cert1 ON cert1.id = finds.objecttypecert
	LEFT JOIN certaintytypes AS cert2 ON cert2.id = finds.objdate1cert
	LEFT JOIN certaintytypes AS cert3 ON cert3.id = finds.objdate2cert
	LEFT JOIN certaintytypes AS cert4 ON cert4.id = coins.ruler_qualifier
	LEFT JOIN certaintytypes AS cert5 ON cert5.id = coins.mint_qualifier
	LEFT JOIN certaintytypes AS cert6 ON cert6.id = coins.denomination_qualifier
	LEFT JOIN certaintytypes AS cert7 ON cert7.id = coins.die_axis_certainty
	LEFT JOIN certaintytypes AS cert8 ON cert8.id = coins. status_qualifier
	LEFT JOIN periods AS period1 ON finds.objdate1period = period1.id
	LEFT JOIN periods AS period2 ON finds.objdate2period = period2.id
	LEFT JOIN periods AS period3 ON finds.culture = period3.id
	LEFT JOIN periods AS period4 ON finds.reuse_period  = period4.id
	LEFT JOIN datequalifiers AS dq1 ON finds.objdate1cert = dq1.id
	LEFT JOIN datequalifiers AS dq2 ON finds.objdate2cert = dq2.id
	LEFT JOIN materials AS mat1 ON finds.material1 = mat1.id
	LEFT JOIN materials AS mat2 ON finds.material2 = mat2.id
	LEFT JOIN manufactures ON finds.manmethod = manufactures.id
	LEFT JOIN surftreatments AS surfaces ON finds.surftreat = surfaces.id
	LEFT JOIN weartypes AS wear ON finds.wear = wear.id
	LEFT JOIN preservations AS p ON finds.preservation = p.id
	LEFT JOIN completeness AS c ON finds.completeness = c.id
	LEFT JOIN people ON finds.finderID = people.secuid
	LEFT JOIN datequalifiers AS dq3 ON finds.datefound1qual = dq3.id
	LEFT JOIN datequalifiers AS dq4 ON finds.datefound2qual  = dq4.id
	LEFT JOIN discmethods AS disco ON finds.discmethod = disco.id
	LEFT JOIN people AS pep2 ON finds.recorderID = pep2.secuid
	LEFT JOIN people AS pep3 ON finds.identifier1ID = pep3.secuid
	LEFT JOIN people AS pep4 ON finds.identifier2ID = pep4.secuid
	LEFT JOIN people AS pep5 ON findspots.landowner = pep5.secuid
	LEFT JOIN subsequentActions AS sub ON finds.subs_action = sub.id
	LEFT JOIN subperiods AS sp  ON finds.objdate1period = sp.id
	LEFT JOIN subperiods AS sp2 ON finds.objdate2period = sp2.id
	LEFT JOIN decmethods AS dm ON finds.decmethod = dm.id
	LEFT JOIN decstyles AS ds ON finds.decstyle = ds.id
	LEFT JOIN denominations AS denoms ON coins.denomination = denoms.id
	LEFT JOIN mints ON coins.mint_id = mints.id
	LEFT JOIN statuses AS sta ON coins.status = sta.id
	LEFT JOIN rulers ON coins.ruler_id = rulers.id
	LEFT JOIN landuses AS lu1 ON findspots.landusecode = lu1.id
	LEFT JOIN landuses AS lu2 ON findspots.landusevalue = lu2.id
	WHERE 1';

	if(isset($params['regionID']) && ($params['regionID'] != ""))
	{
	$regionID = $params['regionID'];
	$sql .= '  AND findspots.regionID = "' . $regionID . '"';
	}

	if(isset($params['old_findID']) && ($params['old_findID'] != ""))
	{
	$old_findID = $params['old_findID'];
	$sql .= '  AND old_findID = "' . $old_findID . '" ';
	}

	if(isset($params['objecttype']) && ($params['objecttype'] != ""))
	{
	$objecttype = $params['objecttype'];
	$sql .= '  AND finds.objecttype = "' . $objecttype . '"';
	}


	if(isset($params['wear']) && ($params['wear'] != ""))
	{
	$wear = $params['wear'];
	$sql .= ' AND finds.degree_of_wear = "' . $wear . '"';
	}
	//Description
	if(isset($params['description']) && ($params['description'] != ""))
	{
	$description = $params['description'];
	$sql .= ' AND finds.description LIKE "%' . $description . '%"';
	}
	//Notes
	if(isset($params['notes']) && ($params['notes'] != ""))
	{
	$notes = $params['notes'];
	$sql .= ' AND finds.notes LIKE "%' . $notes . '%"';
	}
	//Broadperiod
	if(isset($params['broadperiod']) && ($params['broadperiod'] != ""))
	{
	$broadperiod = $params['broadperiod'];
	$sql .= ' AND broadperiod = "' . (string)$broadperiod . '"';
	}
	//Period From date
	if(isset($params['periodfrom']) && ($params['periodfrom'] != ""))
	{
	$periodfrom = $params['periodfrom'];
	$sql .= ' AND finds.objdate1period = "' . (int)$periodfrom . '"';
	}
	//culture
	if(isset($params['culture']) && ($params['culture'] != ""))
	{
	$culture = $params['culture'];
	$sql .= ' AND finds.culture = "' . (int)$culture . '"';
	}

	//From date
	if(isset($params['from']) && ($params['from'] != ""))
	{
	$from = $params['from'];
	$sql .= ' AND finds.numdate1 >= "' . (int)$from . '" AND finds.numdate1 IS NOT NULL';
	}
	if(isset($params['fromend']) && ($params['fromend'] != ""))
	{
	$fromend = $params['fromend'];
	$sql .= ' AND finds.numdate1 <= "' . $fromend .'" AND finds.numdate1 IS NOT NULL';
	}
	//Early mid late
	if(isset($params['tosubperiod']) && ($params['tosubperiod'] != ""))
	{
	$tosubperiod = $params['tosubperiod'];
	$sql .= ' AND finds.objdate2subperiod = "' . (int)$tosubperiod . '"';
	}
	//Period to date
	if(isset($params['periodto']) && ($params['periodto'] != ""))
	{
	$periodto = $params['periodto'];
	$sql .= ' AND finds.objdate2period = "' . $periodto . '"';
	}
	//Early Mid/late
	if(isset($params['fromsubperiod']) && ($params['fromsubperiod'] != ""))
	{
	$fromsubperiod = $params['fromsubperiod'];
	$sql .= ' AND finds.objdate1subperiod = "' . $fromsubperiod . '"';
	}

	//Discmethod
	if(isset($params['discmethod']) && ($params['discmethod'] != ""))
	{
	$discmethod = $params['discmethod'];
	$sql .= ' AND finds.discmethod = "' . $discmethod . '"';
	}

	//To date
	if(isset($params['to']) && ($params['to'] != ""))
	{
	$to = $params['to'];
	$sql .= ' AND finds.numdate2 <= "' . $to . '"';
	}
	//Primary material
	if(isset($params['material']) && ($params['material'] != ""))
	{
	$material = $params['material'];
	$sql .= ' AND finds.material1 = "' . $material . '"';
	}
	//Created by
	if(isset($params['createdby']) && ($params['createdby'] != ""))
	{
	$createdby = $params['createdby'];
	$sql .= ' AND finds.createdBy = "' . $createdby . '"';
	}
	//Finder
	if(isset($params['finderID']) && ($params['finderID'] != ""))
	{
	$finder = $params['finderID'];
	$sql .= ' AND finds.finderID = "' . $finder . '"';
	}
	//Identifier
	if(isset($params['idby']) && ($params['idby'] != ""))
	{
	$idby = $params['idby'];
	$sql .= ' AND finds.identifier1ID = "' . $idby . '"';
	}
	if(isset($params['idby2']) && ($params['idby2'] != ""))
	{
	$idby2 = $params['idby2'];
	$sql .= ' AND finds.identifier2ID = "' . $idby2 . '"';
	}
	//Recorded by
	if(isset($params['recorderID']) && ($params['recorderID'] != ""))
	{
	$recordby = $params['recorderID'];
	$sql .= ' AND finds.recorderID = "' . $recordby . '"';
	}
	//Created on exactly
	if(isset($params['created']) && ($params['created'] != ""))
	{
	$created = $params['created'];
	$sql .= ' AND DATE(finds.created) = "' . $created . '"';
	}

	//Created on
	if(isset($params['createdAfter']) && ($params['createdAfter'] != ""))
	{
	$createdAfter = $params['createdAfter'];
	$sql .= ' AND DATE(finds.created) >= "' . $createdAfter . '"';
	}
	//Created before
	if(isset($params['createdBefore']) && ($params['createdBefore'] != ""))
	{
	$createdBefore = $params['createdBefore'];
	$sql .= ' AND DATE(finds.created) <= "' . $createdBefore . '"';
	}
	//Workflow
	if(isset($params['workflow']) && ($params['workflow'] != ""))
	{
	$workflow = $params['workflow'];
	$sql .= ' AND finds.secwfstage = "' . $workflow . '"';
	}
	//Decoration method
	if(isset($params['decoration']) && ($params['decoration'] != ""))
	{
	$decoration = $params['decoration'];
	$sql .= ' AND finds.decmethod = "' . $decoration . '"';
	}
	//Decoration style
	if(isset($params['decstyle']) && ($params['decstyle'] != ""))
	{
	$decstyle = $params['decstyle'];
	$sql .= ' AND finds.decstyle = "' . $decstyle . '"';
	}

	//Manufacture method
	if(isset($params['manufacture']) && ($params['manufacture'] != ""))
	{
	$manufacture = $params['manufacture'];
	$sql .= ' AND finds.manmethod = "' . $manufacture . '"';
	}
	//Surface treatment
	if(isset($params['surface']) && ($params['surface'] != ""))
	{
	$surface = $params['surface'];
	$sql .= ' AND finds.surftreat = "' .  $surface . '"';
	}
	//Classification
	if(isset($params['class']) && ($params['class'] != ""))
	{
	$class = $params['class'];
	$sql .= ' AND finds.classification LIKE "%' . $class . '%"';
	}
	//Subclassification
	if(isset($params['subclass']) && ($params['subclass'] != ""))
	{
	$subclass = $params['subclass'];
	$sql .= ' AND finds.subclass LIKE "%' . $subclass . '%"';
	}
	//Treasure
	if(isset($params['treasure']) && ($params['treasure'] != ""))
	{
	$treasure = $params['treasure'];
	$sql .= ' AND finds.treasure = "' . $treasure . '"';
	}
	//Treasure number
	if(isset($params['TID']) && ($params['TID'] != ""))
	{
	$treasureID = $params['TID'];
	$sql .= ' AND finds.treasureID = "' . $treasureID . '"';
	}
	//Hoard
	if(isset($params['hoard']) && ($params['hoard'] != ""))
	{
	$hoard = $params['hoard'];
	$sql .= ' AND finds.hoard = "' . $hoard . '"';
	}
	//Hoard name
	if(isset($params['hID']) && ($params['hID'] != ""))
	{
	$hoard = $params['hID'];
	$sql .= ' AND finds.hoardID = "' .  $hoard . '"';
	}
	//Rally
	if(isset($params['rally']) && ($params['rally'] != ""))
	{
	$rally = $params['rally'];
	$sql .= ' AND finds.rally = "' . $rally . '"';
	}
	//Rally name
	if(isset($params['rallyID']) && ($params['rallyID'] != ""))
	{
	$rallyID = $params['rallyID'];
	$sql .= ' AND finds.rallyID = "' .  $rallyID . '"';
	}
	//find of note
	if(isset($params['note']) && ($params['note'] != ""))
	{
	$note = $params['note'];
	$sql .= ' AND finds.findofnote = "' . $note . '"';
	}
	//find of note reason
	if(isset($params['reason']) && ($params['reason'] != ""))
	{
	$reason = $params['reason'];
	$sql .= ' AND finds.findofnotereason = "' . $reason . '"';
	}
	//Other reference
	if(isset($params['otherref']) && ($params['otherref'] != ""))
	{
	$otherref = $params['otherref'];
	$sql .= ' AND finds.other_ref = "' .  $otherref . '"';
	}
	##Coin specific query formation
	//Primary ruler
	if(isset($params['ruler']) && ($params['ruler'] != ""))
	{
	$ruler = $params['ruler'];
	$sql .= ' AND coins.ruler_id = "' . $ruler . '"';
	}
	//Secondary ruler
	if(isset($params['ruler2']) && ($params['ruler2'] != ""))
	{
	$ruler2 = $params['ruler2'];
	$sql .= ' AND coins.ruler2_id = "' .  $ruler2 . '"';
	}
	//Denomination
	if(isset($params['denomination']) && ($params['denomination'] != ""))
	{
	$denomname = $params['denomination'];
	$sql .= ' AND coins.denomination = "' . $denomname . '"';
	}
	//Mint
	if(isset($params['mint']) && ($params['mint'] != ""))
	{
	$mint = $params['mint'];
	$sql .= ' AND coins.mint_id = "' . $mint . '"';
	}
	//Die axis
	if(isset($params['axis']) && ($params['axis'] != ""))
	{
	$axis = $params['axis'];
	$sql .= ' AND coins.die_axis_measurement = "' . $axis . '"';
	}
	//Moneyer
	if(isset($params['moneyer']) && ($params['moneyer'] != ""))
	{
	$moneyer = $params['moneyer'];
	$sql .= ' AND coins.moneyer = "' . $moneyer . '"';
	}
	//Obverse inscription
	if(isset($params['obinsc']) && ($params['obinsc'] != ""))
	{
	$obinsc = $params['obinsc'];
	$sql .= ' AND coins.obverse_inscription LIKE "%' . $obinsc . '%"';
	}
	//Obverse description
	if(isset($params['obdesc']) && ($params['obdesc'] != ""))
	{
	$obdesc = $params['obdesc'];
	$sql .= ' AND coins.obverse_description LIKE "%' . $obdesc . '%"';
	}
	//Reverse inscription
	if(isset($params['revinsc']) && ($params['revinsc'] != ""))
	{
	$revinsc = $params['revinsc'];
	$sql .= ' AND coins.reverse_inscription LIKE "%' . $revinsc . '%"';
	}
	//Reverse description
	if(isset($params['revdesc']) && ($params['revdesc'] != ""))
	{
	$revdesc = $params['revdesc'];
	$sql .= ' AND coins.reverse_description LIKE "%' . $revdesc . '%"';
	}
	##Iron age specific
	//Mack type
	if(isset($params['mack']) && ($params['mack'] != ""))
	{
	$mack = $params['mack'];
	$sql .= ' AND coins.mack_type = "' . $mack . '"';
	}
	//Allen type
	if(isset($params['allen']) && ($params['allen'] != ""))
	{
	$allen = $params['allen'];
	$sql .= ' AND coins.allen_type = "' . $allen . '"';
	}
	//Rudd type
	if(isset($params['rudd']) && ($params['rudd'] != ""))
	{
	$rudd = $params['rudd'];
	$sql .= ' AND coins.rudd_type = "' . $rudd . '"';
	}
	//Van Arsdell type
	if(isset($params['va']) && ($params['va'] != ""))
	{
	$va = $params['va'];
	$sql .= ' AND coins.va_type = "' . $va . '"';
	}
	//Geographical region
	if(isset($params['geoIA']) && ($params['geoIA'] != ""))
	{
	$geography = $params['geoIA'];
	$sql .= ' AND coins.geographyID = "' . $geography . '"';
	}
	//Tribe
	if(isset($params['tribe']) && ($params['tribe'] != ""))
	{
	$tribe = $params['tribe'];
	$sql .= ' AND coins.tribe = "' . $tribe . '"';
	}
	#####
	##Roman specific
	#####
	//ReeceID
	if(isset($params['reeceID']) && ($params['reeceID'] != ""))
	{
	$reeceID = $params['reeceID'];
	$sql .= ' AND coins.reeceID = "' .  $reeceID . '"';
	}
	//Reverse type
	if(isset($params['reverse']) && ($params['reverse'] != ""))
	{
	$reverse = $params['reverse'];
	$sql .= ' AND coins.revtypeID = "' . $reverse . '"';
	}
	####
	##Medieval specific
	####
	//Medieval type
	if(isset($params['medtype']) && ($params['medtype'] != ""))
	{
	$typeID = $params['medtype'];
	$sql .= ' AND coins.typeID = "' . $typeID . '"';
	}
	//Medieval category
	if(isset($params['category']) && ($params['category'] != ""))
	{
	$categoryID = $params['category'];
	$sql .= ' AND coins.categoryID = "' . $categoryID . '"';
	}
	####
	##Greek and roman prov specific
	####
	//Greek state ID
	if(isset($params['greekID']) && ($params['greekID'] != ""))
	{
	$greekstateID = $params['greekID'];
	$sql .= ' AND coins.greekstateID = "' . $greekstateID . '"';
	}
	##Spatial specific query formation
	//County
	if(isset($params['county']) && ($params['county'] != ""))
	{
	$county = $params['county'];
	$sql .= ' AND findspots.county = "' . $county . '"';
	}
	//District
	if(isset($params['district']) && ($params['district'] != ""))
	{
	$district = $params['district'];
	$sql .= ' AND findspots.district = "' . $district . '"';
	}
	//Parish
	if( isset($params['parish']) && ($params['parish'] != "") && in_array($role,$this->restricted) )
	{
	$parish = $params['parish'];
	$sql .= ' AND findspots.parish = "' . $parish .'" AND findspots.knownas IS NULL';
	}
	else if( isset($params['parish']) && ($params['parish'] != "") && in_array($role,$this->higherlevel) )
	{
	$parish = $params['parish'];
	$sql .= ' AND findspots.parish = "' . $parish . '"';
	}
	else if( isset($params['parish']) && ($params['parish'] != "") && in_array($role,$this->_research) )
	{
	$parish = $params['parish'];
	$sql .= ' AND findspots.parish = "' . $parish . '"';
	}
	//Region
	if(isset($params['regionID']) && ($params['regionID'] != ""))
	{
	$region = $params['regionID'];
	$sql .= ' AND findspots.regionID = "' . $region . '"';
	}
	//Landuse
	if(isset($params['landuse']) && ($params['landuse'] != ""))
	{
	$landuse = $params['landuse'];
	$sql .= ' AND findspots.landusecode = "' . $landuse . '"';
	}
	//Secondary landuse
	if(isset($params['value']) && ($params['value'] != ""))
	{
	$value = $params['value'];
	$sql .= ' AND findspots.landusevalue = "' . $value . '"';
	}
	//Comments
	if(isset($params['fourfigure']) && ($params['fourfigure'] != ""))
	{
	$fourfigure = $params['fourfigure'];
	$sql .= ' AND findspots.fourFigure = "' .  $fourfigure . '"';
	}
	//Known as
	if(isset($params['knownas']) && ($params['knownas'] != ""))
	{
	$knownas = $params['knownas'];
	$sql .= ' AND findspots.knownas = "'. $knownas.'"';
	}
	//Known as
	if(isset($params['discovered']) && ($params['discovered'] != ""))
	{
	$discovered = $params['discovered'];
	$sql .= ' AND DATE)(finds.datefound1) >= "'.$discovered.'-01-01" AND DATE(finds.datefound1) <= "'.$discovered.'-12-31" AND DATE(finds.datefound1) IS NOT NULL';
	};
	$finds = $this->getAdapter();

	$data = $finds->fetchAll($sql);
	return $data;
	}
}