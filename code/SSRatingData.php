<?php
/**
 * Represents a single Rating on a page
 * @package ssratings
 * @author rgodinho
 */
class SSRatingData extends DataObject {
	
	static $db = array("Rate" => "Int", "SessionID" => "Varchar(255)");

	static $has_one = array("Parent" => "SiteTree");
	
	static $has_many = array();
	
	static $many_many = array();
	
	static $defaults = array();
	
	/**
	 * This method is called just before this object is
	 * written to the database.
	 */
	public function onBeforeWrite() {
		parent::onBeforeWrite();		
	}	
}