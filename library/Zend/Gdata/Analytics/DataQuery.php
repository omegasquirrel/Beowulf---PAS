<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Gdata
 * @subpackage Analytics
 * @copyright  Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id$
 */

/**
 * @see Zend_Gdata_Query
 */
require_once 'Zend/Gdata/Query.php';

/**
 * @category   Zend
 * @package    Zend_Gdata
 * @subpackage Analytics
 */
class Zend_Gdata_Analytics_DataQuery extends Zend_Gdata_Query
{
    const ANALYTICS_FEED_URI = 'https://www.googleapis.com/analytics/v2.4/data';

    /**
     * The default URI used for feeds.
     */
    protected $_defaultFeedUri = self::ANALYTICS_FEED_URI;

    // D1. Visitor
    const DIMENSION_BROWSER = 'ga:browser';
    const DIMENSION_BROWSER_VERSION = 'ga:browserVersion';
    
    const DIMENSION_CITY = 'ga:city';
    const DIMENSION_CONNECTIONSPEED = 'ga:connectionSpeed';
    const DIMENSION_CONTINENT = 'ga:continent';
	const DIMENSION_SUB_CONTINENT = 'ga:subContinent';
    const DIMENSION_COUNTRY = 'ga:country';
    const DIMENSION_METRO = 'ga:metro';
    
    const DIMENSION_DATE = 'ga:date';
    const DIMENSION_YEAR = 'ga:year';
	const DIMENSION_MONTH = 'ga:month';
    const DIMENSION_WEEK = 'ga:week';
    const DIMENSION_DAY = 'ga:day';
	const DIMENSION_HOUR = 'ga:hour';
    const DIMENSION_DAY_OF_WEEK = 'ga:dayOfWeek';
    
	const DIMENSION_DAYS_SINCE_LAST_VISIT= 'ga:daysSinceLastVisit';
    const DIMENSION_FLASH_VERSION = 'ga:flashVersion';
    const DIMENSION_HOSTNAME = 'ga:hostname';
    const DIMENSION_JAVA_ENABLED= 'ga:javaEnabled';
    const DIMENSION_LANGUAGE= 'ga:language';
    const DIMENSION_LATITUDE = 'ga:latitude';
    const DIMENSION_LONGITUDE = 'ga:longitude';

    const DIMENSION_NETWORK_DOMAIN = 'ga:networkDomain';
    const DIMENSION_NETWORK_LOCATION = 'ga:networkLocation';
    const DIMENSION_OPERATING_SYSTEM = 'ga:operatingSystem';
    const DIMENSION_OPERATING_SYSTEM_VERSION = 'ga:operatingSystemVersion';
    const DIMENSION_PAGE_DEPTH = 'ga:pageDepth';
    const DIMENSION_REGION = 'ga:region';
    const DIMENSION_SCREEN_COLORS= 'ga:screenColors';
    const DIMENSION_SCREEN_RESOLUTION = 'ga:screenResolution';
    const DIMENSION_USER_DEFINED_VALUE = 'ga:userDefinedValue';
    const DIMENSION_VISIT_COUNT = 'ga:visitCount';
    const DIMENSION_VISIT_LENGTH = 'ga:visitLength';
    const DIMENSION_VISITOR_TYPE = 'ga:visitorType';

    const DIMENSION_IS_MOBILE = 'ga:isMobile';
    const DIMENSION_MOBILE_DEVICE_BRANDING = 'ga:mobileDeviceBranding';
    const DIMENSION_MOBILE_DEVICE_MODEL = 'ga:mobileDeviceModel';
    const DIMENSION_MOBILE_INPUT_SELECTOR = 'ga:mobileInputSelector';
    const DIMENSION_MOBILE_DEVICE_INFO = 'ga:mobileDeviceInfo';

    // D2. Campaign
    const DIMENSION_AD_CONTENT = 'ga:adContent';
    const DIMENSION_AD_GROUP = 'ga:adGroup';
    const DIMENSION_AD_SLOT = 'ga:adSlot';
    const DIMENSION_AD_SLOT_POSITION = 'ga:adSlotPosition';
    const DIMENSION_CAMPAIGN = 'ga:campaign';
    const DIMENSION_KEYWORD = 'ga:keyword';
    const DIMENSION_MEDIUM = 'ga:medium';
    const DIMENSION_REFERRAL_PATH = 'ga:referralPath';
    const DIMENSION_SOURCE = 'ga:source';
    const DIMENSION_SOCIAL_NETWORK = 'ga:socialNetwork';
    const DIMENSION_SOCIAL_SOURCE_REFERRAL = 'ga:hasSocialSourceReferral';
    const DIMENSION_AD_DISTRIBUTION_NETWORK = 'ga:adDistributionNetwork';
    const DIMENSION_AD_MATCH_TYPE = 'ga:adMatchType';
    const DIMENSION_AD_MATCHED_QUERY = 'ga:adMatchedQuery';
    const DIMENSION_AD_PLACEMENT_DOMAIN = 'ga:adPlacementDomain';
    const DIMENSION_AD_PLACEMENT_URL = 'ga:adPlacementUrl';
    const DIMENSION_AD_FORMAT = 'ga:adFormat';
    const DIMENSION_AD_TARGETING_TYPE = 'ga:adTargetingType';
    const DIMENSION_AD_TARGETING_OPTION = 'ga:adTargetingOption';
    const DIMENSION_AD_DISPLAY_URL = 'ga:adDisplayUrl';
    const DIMENSION_AD_DESTINATION_URL = 'ga:adDestinationUrl';
    const DIMENSION_AD_WORDS_CUSTOMER_ID = 'ga:adwordsCustomerID';
    const DIMENSION_AD_WORDS_CAMPAIGN_ID = 'ga:adwordsCampaignID';
    const DIMENSION_AD_WORDS_GROUP_ID = 'ga:adwordsAdGroupID';
    const DIMENSION_AD_WORDS_CREATIVE_ID = 'ga:adwordsCreativeID';
    const DIMENSION_AD_WORDS_CRITERIA_ID = 'ga:adwordsCriteriaID';

    // D3. Content
    const DIMENSION_EXIT_PAGE_PATH = 'ga:exitPagePath';
    const DIMENSION_LANDING_PAGE_PATH = 'ga:landingPagePath';
    const DIMENSION_PAGE_PATH = 'ga:pagePath';
    const DIMENSION_PAGE_TITLE = 'ga:pageTitle';
    const DIMENSION_SECOND_PAGE_PATH = 'ga:secondPagePath';


    // D4. Ecommerce
    const DIMENSION_AFFILIATION = 'ga:affiliation';
    const DIMENSION_DAYS_TO_TRANSACTION = 'ga:daysToTransaction';
    const DIMENSION_PRODUCT_CATEGORY = 'ga:productCategory';
    const DIMENSION_PRODUCT_NAME = 'ga:productName';
    const DIMENSION_PRODUCT_SKU = 'ga:productSku';
    const DIMENSION_TRANSACTION_ID = 'ga:transactionId';
    const DIMENSION_VISITS_TO_TRANSACTION = 'ga:visitsToTransaction';

    // D5. Internal Search
    const DIMENSION_SEARCH_CATEGORY = 'ga:searchCategory';
    const DIMENSION_SEARCH_DESTINATION_PAGE = 'ga:searchDestinationPage';
    const DIMENSION_SEARCH_KEYWORD = 'ga:searchKeyword';
    const DIMENSION_SEARCH_KEYWORD_REFINEMENT = 'ga:searchKeywordRefinement';
    const DIMENSION_SEARCH_START_PAGE = 'ga:searchStartPage';
    const DIMENSION_SEARCH_USED = 'ga:searchUsed';

    // D6. Navigation
    const DIMENSION_NEXT_PAGE_PATH = 'ga:nextPagePath';
    const DIMENSION_PREV_PAGE_PATH= 'ga:previousPagePath';
    const DIMENSION_PAGE_PATH_LEVEL_1 = 'ga:pagePathLevel1';
	const DIMENSION_PAGE_PATH_LEVEL_2 = 'ga:pagePathLevel2';
	const DIMENSION_PAGE_PATH_LEVEL_3 = 'ga:pagePathLevel3';
	const DIMENSION_PAGE_PATH_LEVEL_4 = 'ga:pagePathLevel4';

    // D7. Events
    const DIMENSION_EVENT_CATEGORY = 'ga:eventCategory';
    const DIMENSION_EVENT_ACTION = 'ga:eventAction';
    const DIMENSION_EVENT_LABEL = 'ga:eventLabel';

    // D8. Custom Variables
    const DIMENSION_CUSTOM_VAR_NAME_1 = 'ga:customVarName1';
    const DIMENSION_CUSTOM_VAR_NAME_2 = 'ga:customVarName2';
    const DIMENSION_CUSTOM_VAR_NAME_3 = 'ga:customVarName3';
    const DIMENSION_CUSTOM_VAR_NAME_4 = 'ga:customVarName4';
    const DIMENSION_CUSTOM_VAR_NAME_5 = 'ga:customVarName5';
    const DIMENSION_CUSTOM_VAR_VALUE_1 = 'ga:customVarValue1';
    const DIMENSION_CUSTOM_VAR_VALUE_2 = 'ga:customVarValue2';
    const DIMENSION_CUSTOM_VAR_VALUE_3 = 'ga:customVarValue3';
    const DIMENSION_CUSTOM_VAR_VALUE_4 = 'ga:customVarValue4';
    const DIMENSION_CUSTOM_VAR_VALUE_5 = 'ga:customVarValue5';

    //D9. Social 
    const DIMENSION_SOCIAL_ACTIVITY_ENDORSING_URL = 'ga:socialActivityEndorsingUrl';
    const DIMENSION_SOCIAL_ACTIVITY_DISPLAY_NAME = 'ga:socialActivityDisplayName';
    const DIMENSION_SOCIAL_ACTIVITY_POST = 'ga:socialActivityPost';
    const DIMENSION_SOCIAL_ACTIVITY_TIMESTAMP = 'ga:socialActivityTimestamp';
    const DIMENSION_SOCIAL_ACTIVITY_USER_HANDLE = 'ga:socialActivityUserHandle';
    const DIMENSION_SOCIAL_ACTIVITY_USER_PHOTO_URL = 'ga:socialActivityUserPhotoUrl';
    const DIMENSION_SOCIAL_ACTIVITY_USER_PROFILE_URL = 'ga:socialActivityUserProfileUrl';
    const DIMENSION_SOCIAL_ACTIVITY_CONTENT_URL = 'ga:socialActivityContentUrl';
    const DIMENSION_SOCIAL_ACTIVITY_TAGS_SUMMARY = 'ga:socialActivityTagsSummary';
    const DIMENSION_SOCIAL_ACTIVITY_ACTION = 'ga:socialActivityAction';
    const DIMENSION_SOCIAL_ACTIVITY_NETWORK_ACTION = 'ga:socialActivityNetworkAction';
    
    const DIMENSION_SOCIAL_INTERACTION_NETWORK = 'ga:socialInteractionNetwork';
	const DIMENSION_SOCIAL_INTERACTION_ACTION = 'ga:socialInteractionAction';
	const DIMENSION_SOCIAL_INTERACTION_NETWORK_ACTION = 'ga:socialInteractionNetworkAction';
	const DIMENSION_SOCIAL_INTERACTION_TARGET = 'ga:socialInteractionTarget';
    
	//D10. User timings
	const DIMENSION_USER_TIMING_CATEGORY = 'ga:userTimingCategory';
	const DIMENSION_USER_TIMING_LABEL = 'ga:userTimingLabel';
	const DIMENSION_USER_TIMING_VARIABLE = 'ga:userTimingVariable'; 
	
    // M1. Visitor
    const METRIC_BOUNCES = 'ga:bounces';
    const METRIC_ENTRANCES = 'ga:entrances';
    const METRIC_EXITS = 'ga:exits';
    const METRIC_NEW_VISITS = 'ga:newVisits';
    const METRIC_PAGEVIEWS = 'ga:pageviews';
    const METRIC_TIME_ON_PAGE = 'ga:timeOnPage';
    const METRIC_TIME_ON_SITE = 'ga:timeOnSite';
    const METRIC_VISITORS = 'ga:visitors';
    const METRIC_VISITS = 'ga:visits';
    const METRIC_ORGANIC_SEARCHES = 'ga:organicSearches';

    // M2. Campaign
    const METRIC_AD_CLICKS = 'ga:adClicks';
    const METRIC_AD_COST = 'ga:adCost';
    const METRIC_CPC = 'ga:CPC';
    const METRIC_CPM = 'ga:CPM';
    const METRIC_CTR = 'ga:CTR';
    const METRIC_IMPRESSIONS = 'ga:impressions';

    // M3. Content
    const METRIC_UNIQUE_PAGEVIEWS = 'ga:uniquePageviews';

    // M4. Ecommerce
    const METRIC_ITEM_REVENUE = 'ga:itemRevenue';
    const METRIC_ITEM_QUANTITY = 'ga:itemQuantity';
    const METRIC_TRANSACTIONS = 'ga:transactions';
    const METRIC_TRANSACTION_REVENUE = 'ga:transactionRevenue';
    const METRIC_TRANSACTION_SHIPPING = 'ga:transactionShipping';
    const METRIC_TRANSACTION_TAX = 'ga:transactionTax';
    const METRIC_UNIQUE_PURCHASES = 'ga:uniquePurchases';

    // M5. Internal Search
    const METRIC_SEARCH_DEPTH = 'ga:searchDepth';
    const METRIC_SEARCH_DURATION = 'ga:searchDuration';
    const METRIC_SEARCH_EXITS = 'ga:searchExits';
    const METRIC_SEARCH_REFINEMENTS = 'ga:searchRefinements';
    const METRIC_SEARCH_UNIQUES = 'ga:searchUniques';
    const METRIC_SEARCH_VISIT = 'ga:searchVisits';
    const METRIC_SEARCH_RESULT_VIEWS = 'ga:searchResultViews';

    // M6. Goals
    const METRIC_GOAL_COMPLETIONS_ALL = 'ga:goalCompletionsAll';
    const METRIC_GOAL_STARTS_ALL = 'ga:goalStartsAll';
    const METRIC_GOAL_VALUE_ALL = 'ga:goalValueAll';
    
    const METRIC_GOAL_1_COMPLETION = 'ga:goal1Completions';
    const METRIC_GOAL_1_STARTS = 'ga:goal1Starts';
    const METRIC_GOAL_1_VALUE = 'ga:goal1Value';

	const METRIC_GOAL_2_COMPLETION = 'ga:goal2Completions';
    const METRIC_GOAL_2_STARTS = 'ga:goal2Starts';
    const METRIC_GOAL_2_VALUE = 'ga:goal2Value';
        
    const METRIC_GOAL_3_COMPLETION = 'ga:goal3Completions';
    const METRIC_GOAL_3_STARTS = 'ga:goal3Starts';
    const METRIC_GOAL_3_VALUE = 'ga:goal3Value';
        
    const METRIC_GOAL_4_COMPLETION = 'ga:goal4Completions';
    const METRIC_GOAL_4_STARTS = 'ga:goal4Starts';
    const METRIC_GOAL_4_VALUE = 'ga:goal4Value';
    
	const METRIC_GOAL_5_COMPLETION = 'ga:goal5Completions';
    const METRIC_GOAL_5_STARTS = 'ga:goal5Starts';
    const METRIC_GOAL_5_VALUE = 'ga:goal5Value';
    
	const METRIC_GOAL_6_COMPLETION = 'ga:goal6Completions';
    const METRIC_GOAL_6_STARTS = 'ga:goal6Starts';
    const METRIC_GOAL_6_VALUE = 'ga:goal6Value'; 

	const METRIC_GOAL_7_COMPLETION = 'ga:goal7Completions';
    const METRIC_GOAL_7_STARTS = 'ga:goal7Starts';
    const METRIC_GOAL_7_VALUE = 'ga:goal7Value';
    
	const METRIC_GOAL_8_COMPLETION = 'ga:goal8Completions';
    const METRIC_GOAL_8_STARTS = 'ga:goal8Starts';
    const METRIC_GOAL_8_VALUE = 'ga:goal8Value';
    
	const METRIC_GOAL_9_COMPLETION = 'ga:goal9Completions';
    const METRIC_GOAL_9_STARTS = 'ga:goal9Starts';
    const METRIC_GOAL_9_VALUE = 'ga:goal9Value';
    
	const METRIC_GOAL_10_COMPLETION = 'ga:goal10Completions';
    const METRIC_GOAL_10_STARTS = 'ga:goal10Starts';
    const METRIC_GOAL_10_VALUE = 'ga:goal10Value';
    
	const METRIC_GOAL_11_COMPLETION = 'ga:goal11Completions';
    const METRIC_GOAL_11_STARTS = 'ga:goal11Starts';
    const METRIC_GOAL_11_VALUE = 'ga:goal11Value';

	const METRIC_GOAL_12_COMPLETION = 'ga:goal12Completions';
    const METRIC_GOAL_12_STARTS = 'ga:goal12Starts';
    const METRIC_GOAL_12_VALUE = 'ga:goal12Value';
    
	const METRIC_GOAL_13_COMPLETION = 'ga:goal13Completions';
    const METRIC_GOAL_13_STARTS = 'ga:goal13Starts';
    const METRIC_GOAL_13_VALUE = 'ga:goal13Value';

	const METRIC_GOAL_14_COMPLETION = 'ga:goal14Completions';
    const METRIC_GOAL_14_STARTS = 'ga:goal14Starts';
    const METRIC_GOAL_14_VALUE = 'ga:goal14Value';
    
	const METRIC_GOAL_15_COMPLETION = 'ga:goal15Completions';
    const METRIC_GOAL_15_STARTS = 'ga:goal15Starts';
    const METRIC_GOAL_15_VALUE = 'ga:goal15Value';
    
   	const METRIC_GOAL_16_COMPLETION = 'ga:goal16Completions';
    const METRIC_GOAL_16_STARTS = 'ga:goal16Starts';
    const METRIC_GOAL_16_VALUE = 'ga:goal16Value';
    
	const METRIC_GOAL_17_COMPLETION = 'ga:goal17Completions';
    const METRIC_GOAL_17_STARTS = 'ga:goal17Starts';
    const METRIC_GOAL_17_VALUE = 'ga:goal17Value';
    
	const METRIC_GOAL_18_COMPLETION = 'ga:goal18Completions';
    const METRIC_GOAL_18_STARTS = 'ga:goal18Starts';
    const METRIC_GOAL_18_VALUE = 'ga:goal18Value';
    
	const METRIC_GOAL_19_COMPLETION = 'ga:goal19Completions';
    const METRIC_GOAL_19_STARTS = 'ga:goal19Starts';
    const METRIC_GOAL_19_VALUE = 'ga:goal19Value';
    
    const METRIC_GOAL_20_COMPLETION = 'ga:goal20Completions';
    const METRIC_GOAL_20_STARTS = 'ga:goal20Starts';
    const METRIC_GOAL_20_VALUE = 'ga:goal20Value';
    
    
    // M7. Events
    const METRIC_TOTAL_EVENTS = 'ga:totalEvents';
    const METRIC_UNIQUE_EVENTS = 'ga:uniqueEvents';
    const METRIC_EVENT_VALUE = 'ga:eventValue';
    const METRIC_VISITS_WITH_EVENT = 'ga:visitsWithEvent';
    
    //M8. Social
    const METRIC_SOCIAL_ACTIVITIES = 'ga:socialActivities';
    const METRIC_SOCIAL_INTERACTIONS = 'ga:socialInteractions';
	const METRIC_UNIQUE_SOCIAL_INTERACTIONS = 'ga:uniqueSocialInteractions';
	
	//M9. App tracking
	const METRIC_APP_VIEWS = 'ga:appviews';
	const METRIC_UNIQUE_APP_VIEWS = 'ga:uniqueAppviews';
	
	//M10. Site speed
	const METRIC_PAGE_LOAD_TIME = 'ga:pageLoadTime';
	const METRIC_PAGE_LOAD_SAMPLE = 'ga:pageLoadSample';
	const METRIC_DOMAIN_LOOKUP_TIME = 'ga:domainLookupTime';
	const METRIC_PAGE_DOWNLOAD_TIME = 'ga:pageDownloadTime';
	const METRIC_REDIRECTION_TIME = 'ga:redirectionTime';
	const METRIC_SERVER_CONNECTION_TIME = 'ga:serverConnectionTime';
	const METRIC_SERVER_RESPONSE_TIME = 'ga:serverResponseTime';
	const METRIC_SPEED_METRICS_SAMPLE = 'ga:speedMetricsSample';
	const METRIC_SPEED_AVG_PAGE_LOAD_TIME = 'ga:avgPageLoadTime';
	const METRIC_AVG_DOMAIN_LOOKUP_TIME = 'ga:avgDomainLookupTime';
	const METRIC_AVG_PAGE_DOWNLOAD_TIME = 'ga:avgPageDownloadTime';
	
	//M11. User timings
	const METRIC_USER_TIMING_VALUE = 'ga:userTimingValue';
	const METRIC_USER_TIMINGS_SAMPLE = 'ga:userTimingSample';
	
	//M12. Exceptions
	const METRIC_EXCEPTIONS = 'ga:exceptions';
	const METRIC_FATAL_EXCEPTIONS = 'ga:fatalExceptions';
	
    
    // suported filter operators
    const EQUALS = "==";
    const EQUALS_NOT = "!=";
    const GREATER = ">";
    const LESS = ">";
    const GREATER_EQUAL = ">=";
    const LESS_EQUAL = "<=";
    const CONTAINS = "=@";
    const CONTAINS_NOT ="!@";
    const REGULAR ="=~";
    const REGULAR_NOT ="!~";
    
    /**
     * @var string
     */
    protected $_profileId;
    /**
     * @var array
     */
    protected $_dimensions = array();
    /**
     * @var array
     */
    protected $_metrics = array();
    /**
     * @var array
     */
    protected $_sort = array();
    /**
     * @var array
     */
    protected $_filters = array();
    
    /**
     * @param string $id
     * @return Zend_Gdata_Analytics_DataQuery
     */
    public function setProfileId($id)
    {
        $this->_profileId = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getProfileId()
    {
        return $this->_profileId;
    }

    /**
     * @param string $dimension
     * @return Zend_Gdata_Analytics_DataQuery
     */
    public function addDimension($dimension)
    {
        $this->_dimensions[$dimension] = true;        
        return $this;
    }

    /**
     * @param string $metric
     * @return Zend_Gdata_Analytics_DataQuery
     */
    public function addMetric($metric)
    {
        $this->_metrics[$metric] = true;
        return $this;
    }

    /**
     * @return array
     */
    public function getDimensions()
    {
        return $this->_dimensions;
    }

    /**
     * @return array
     */
    public function getMetrics()
    {
        return $this->_metrics;
    }

    /**
     * @param string $dimension
     * @return Zend_Gdata_Analytics_DataQuery
     */
    public function removeDimension($dimension)
    {
        unset($this->_dimensions[$dimension]);
        return $this;
    }
    /**
     * @param string $metric
     * @return Zend_Gdata_Analytics_DataQuery
     */
    public function removeMetric($metric)
    {
        unset($this->_metrics[$metric]);
        return $this;
    }
    /**
     * @param string $value
     * @return Zend_Gdata_Analytics_DataQuery
     */
    public function setStartDate($date)
    {
        $this->setParam("start-date", $date);
        return $this;
    }
    /**
     * @param string $value
     * @return Zend_Gdata_Analytics_DataQuery
     */
    public function setEndDate($date)
    {
        $this->setParam("end-date", $date);
        return $this;
    }
    
    /**
     * @param string $filter
     * @return Zend_Gdata_Analytics_DataQuery
     */
    public function addFilter($filter)
    {
        $this->_filters[] = array($filter, true);
        return $this;
    }
    
    /**
     * @param string $filter
     * @return Zend_Gdata_Analytics_DataQuery
     */
    public function addOrFilter($filter)
    {
        $this->_filters[] = array($filter, false);
        return $this;
    }
    
    /**
     * @param string $sort
     * @param boolean[optional] $descending
     * @return Zend_Gdata_Analytics_DataQuery
     */
    public function addSort($sort, $descending=false)
    {
        // add to sort storage
        $this->_sort[] = ($descending?'-':'').$sort;
        return $this;
    }
    
    /**
     * @return Zend_Gdata_Analytics_DataQuery
     */
    public function clearSort()
    {
        $this->_sort = array();
        return $this;
    }
    
    /**
     * @param string $segment
     * @return Zend_Gdata_Analytics_DataQuery
     */
    public function setSegment($segment)
    {
        $this->setParam('segment', $segment);
        return $this;
    }

    /**
     * @return string url
     */
    public function getQueryUrl()
    {
        $uri = $this->_defaultFeedUri;
        if (isset($this->_url)) {
            $uri = $this->_url;
        }
        
        $dimensions = $this->getDimensions();
        if (!empty($dimensions)) {
            $this->setParam('dimensions', implode(",", array_keys($dimensions)));
        }
        
        $metrics = $this->getMetrics();
        if (!empty($metrics)) {
            $this->setParam('metrics', implode(",", array_keys($metrics)));
        }
        
        // profile id (ga:tableId)
        if ($this->getProfileId() != null) {
            $this->setParam('ids', 'ga:'.ltrim($this->getProfileId(), "ga:"));
        }
                
        // sorting
        if ($this->_sort) {
            $this->setParam('sort', implode(",", $this->_sort));
        }
        
        // filtering
        $filters = "";
        foreach ($this->_filters as $filter) {
            $filters.=($filter[1]===true?';':',').$filter[0];
        }
        
        if ($filters!="") {
            $this->setParam('filters', ltrim($filters, ",;"));
        }
        
        $uri .= $this->getQueryString();
        return $uri;
    }
}
