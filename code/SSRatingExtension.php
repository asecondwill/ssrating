<?php
/**
 * Silverstripe Rating Extension
 * Stars Powered by jQuery UI Stars v2.1.1
 * http://plugins.jquery.com/project/Star_Rating_widget
 * @package ssratings
 * @author rgodinho
 */
class SSRatingExtension extends Extension {
	/**
	 * Rate Average
	 * @var int 
	 */
	protected $rateAverage;
	
	/**
	 * Sum of rates
	 * @var int
	 */
	protected $rateSum;
	
	function extraStatics() {
    return array(
    );
  }

  public function Subject() {
    return $this->owner;
  }

  public function getRateAverage() {
  	return $this->rateAverage;
  }

  public function setRateAverage($rate) {
  	$this->rateAverage = $rate;
  }

  public function getRateSum() {
  	return $this->rateSum;
  }

  public function setRateSum($sum) {
  	$this->rateSum = $sum;
  }  
  
   /**
   * Get Rating
   * @return html rendered
   */
  public function getSSRating() {
		Requirements::javascript(SSRATING . '/javascript/jquery-1.6.4.js');
		Requirements::javascript(SSRATING . '/javascript/ui.core.min.js');
		Requirements::javascript(SSRATING . '/javascript/ui.stars.js');
		Requirements::javascript(SSRATING . '/javascript/jquery-ui-1.7.2.custom.min.js');
		
		Requirements::javascript(SSRATING . '/javascript/rate.js');
		Requirements::javascript(SSRATING . '/javascript/rate-dialog.js');
		
		// -- Extension css file
		$this->includePluginsCss('ssrating.css');
		$this->includePluginsCss('ui.stars.css');
		$this->includePluginsCss('ui-lightness/jquery-ui-1.7.2.custom.css');
  	
    	return $this->owner->renderWith('SSRating');
	}

	/**
	 * Get rated Value
	 * @param $curPageID Current page ID
	 * @return int
	 */
	public function getRateValue($curPageID = null) {
		// -- Get Current Page if necessary
		if (is_null($curPageID)) {
  			$curPageID = $this->getCurrentPageID(); 			
		}

	    /**
	     * To Hold the Rated value
	     * @var int actualRate
	     */
		$actualRate = 0;		
		
		if (!is_null($curPageID)) {
		
			// -- Get The Total Rate for the current Page
			$sqlQuery = new SQLQuery(array('count(SSRatingData.Rate) as sumVotes', 'SUM(SSRatingData.Rate) as rateTotal'), 
				array('SSRatingData'), 
				array("SSRatingData.ParentID = $curPageID"));
	
			// Run the query and get the row
			$result = $sqlQuery->execute()->first();						
			
			if (!is_null($result['rateTotal'])) {
				$actualRate = round((int)$result['rateTotal'] / (int)$result['sumVotes']);	
				
				// -- Update Calculated Data
				$this->setRateAverage($actualRate);
				$this->setRateSum((int)$result['sumVotes']);
			}
		}				

		return $actualRate;
	}
	
	/**
	 * Get the categories 
	 * @return array 
	 */
	protected function getRatingCategories() {
		return array(
			      "1" => _t("SSRatingExtension.STAR1", 'Star 1'),
			      "2" =>  _t("SSRatingExtension.STAR2", 'Star 2'),
			      "3" =>  _t("SSRatingExtension.STAR3", 'Star 3'),
			      "4" =>  _t("SSRatingExtension.STAR4", 'Star 4'),
			      "5" =>  _t("SSRatingExtension.STAR5", 'Star 5')
			   );
	}
	
	/**
	 * Get Stars - Calculated from table data records
	 * Should display disabled stars
	 * @return Form
	 */  
  	public function getRankingStars() {
		/**
		 * variable to hold the current page id
		 * @var int curPageID 
		 */
  		$curPageID = $this->getCurrentPageID(); 
  		
		$actualRate = $this->getRateValue($curPageID);
		
		$rateFields = new FieldSet(
			new OptionsetField($name = "ranking", $title = _t("SSRatingExtension.RANKINGTITLE", 'Ranking'),
   				$source = $this->getRatingCategories(),
				$value = $actualRate
		));		
				
		$actions = new FieldSet(new FormAction('rankingresults', 'ranking'));
		
	  	return new Form($this->getCurrentPageData(), "ranking", $rateFields, $actions);				
	} 	
	
	/**
	 * Get Current page ID
	 * Check if the new Silverstripe 2.4 get_current_page method exist
	 * Avoid Warning error
	 * @return int
	 */
	public function getCurrentPageID() {
		if (method_exists('Director', 'get_current_page')) {
			return Director::get_current_page()->ID;
		}
		else {
			return Director::currentPage()->ID;	
		}
	} 
	
	/**
	 * Get Current Page
	 * Check if the new Silverstripe 2.4 get_current_page method exist
	 * Avoid Warning error 
	 * @return Page Object
	 */
	protected function getCurrentPageData() {
		if (method_exists('Director', 'get_current_page')) {
			return Director::get_current_page();
		}
		else {
			return Director::currentPage();	
		}
	}
	
	/**
	 * Get rating Stars to allow voting
	 * @return Form
	 */  
  	public function getRatingStars() {
		/**
		 * variable to hold the current page id
		 * @var int curPageID 
		 */
  		$curPageID = $this->getCurrentPageID(); 
  			
		$rateFields = new FieldSet(
		    new HiddenField("pageid", "pageid", $curPageID),
			new OptionsetField($name = "rate", $title = _t("SSRatingExtension.RATINGTITLE", 'Rating'),
   				$source = $this->getRatingCategories(),
			   $value = 0
		));		
				
		$actions = new FieldSet(new FormAction('results', 'ratings'));

	  	return new Form($this->getCurrentPageData(), "ratings", $rateFields, $actions);				
	}  

	/**
	 * Get Data from Rate table
	 * @param int $curPageID ariable to hold the current page id
	 * @return array Query Results
	 */
	public function getRateData($curPageID) {
		$sqlQuery = new SQLQuery(array('count(SSRatingData.Rate) as sumVotes', 'SSRatingData.Rate as rate'), 
			array('SSRatingData'), 
			array("SSRatingData.ParentID = $curPageID")
			);
		$sqlQuery->groupby('SSRatingData.Rate');
		$sqlQuery->orderby('rate DESC');
		// Run the query and get the row
		
		$result = $sqlQuery->execute();
		
		return $result;
	}
	
	/**
	 * Check unique rate for current session and page
	 * Easy way to call from a template file (.ss)
	 * Could be used to hide rating options
	 * @return boolean
	 */
	public function isUnique() {
		$curPageID = $this->getCurrentPageID();
		if (!is_null($curPageID)) {
			return $this->isUniqueRate($curPageID, session_id());
		}
		else {
			return false;
		}	
	}
	
	/**
	 * Check unique rate for current session and page
	 * @param string $pageid Page ID
	 * @param string $sessionid Session ID
	 * @return boolean
	 */
	public function isUniqueRate($pageid, $sessionid) {		
		$checkUnique = DataObject::get_one('SSRatingData', "SSRatingData.ParentID=$pageid AND SSRatingData.SessionID = '$sessionid'");
		return !$checkUnique;			
	}
  
  /**
   * include a css, check first in the the theme, then project, in the module or jsparty treeview
   * @param $css
   * @return void
   */
  private function includePluginsCss( $css ) {
    if( Director::fileExists($file = project() . '/themes/' . SSViewer::current_theme() . '/css/' . $css) ) {
      Requirements::css($file);

    }
    elseif( Director::fileExists($file = project() . '/css/' . $css) ) {
      Requirements::css($file);
    }
    elseif( Director::fileExists($file = SSRATING . '/css/' . $css) )  {    	
      Requirements::css(SSRATING . '/css/' . $css);
    }
    else {
    	// -- TODO
    }
  }  
}
/**
 * Rating Management Controller for Ajax actions
 * @author rgodinho
 */
class SSRatingExtension_Controller extends Page_Controller {    
    /**
     * Save Rating Vote
     * Display string with message
     * @return void
     */
	function saveratings() {
		$ratingValue = (int)$_REQUEST['rate'];
		
		// -- Only writes if the user selected a star
		if ($ratingValue > 0) {
	    	$rateData = new SSRatingData();
			// -- Get Current PageID
	    	$rateData->ParentID = (int)$_REQUEST['pageid'];
	    	// -- Get SessionID
	    	$sessionID = session_id();

			$rnk = new SSRatingExtension();

		    // -- Check sessionID unique for current page
	    	if ($rnk->isUniqueRate($rateData->ParentID, $sessionID)) {
	    		$rateData->Rate = $ratingValue;

		    	// -- Write the session id
		    	if (!is_null($sessionID)) {
		    		$rateData->SessionID = $sessionID;	
		    	}

		    	$rateData->write();	    		
	    	}  
	    	  	
	    	// -- Return the updated ranking	    	
			$rnk->getRateValue($rateData->ParentID);
			 
	    	//echo '(' . _t('SSRatingExtension.RATEAVERAGE', 'Average: ') . $rnk->getRateAverage() .' - ' . _t('SSRatingExtension.RATESUM', 'Votes: ') . $rnk->getRateSum() . ')';	    
	    	
	    	
	    	$json = Convert::array2json(array('average'=>$rnk->getRateAverage(), 'votes'=> $rnk->getRateSum()));
	    	echo $json;		    	
		}
    }
    
    /**
     * Get Rated Data by Group 
     * The function Echo's the javascript to build the Graph
     * @return void
     */
    function getratingsdata() {
    	
    	$dataResults = '';
    	
    	if (isset($_REQUEST['pageid'])) {
	    	// -- Get the Data
			$rnkExt = new SSRatingExtension();
			$queryResult = $rnkExt->getRateData((int)$_REQUEST['pageid']);    	
	    					
	    	/**
	    	 * For Graph Data
	    	 * @var string
	    	 */
	    	$dataResults = '';
	    	    	
	    	// -- Iterate the records
			foreach($queryResult as $row) {
				if (!empty($dataResults)) {
					$dataResults.='<br/>';
				}
				$dataResults.=   _t('SSRatingExtension.STARLABEL', 'Star ') . $row['rate'] . ' - ' .  _t('SSRatingExtension.RATESUM', 'Votes') . $row['sumVotes'];
			}	

			echo $dataResults;
	     }
    }
}