<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
class GD_DataQuery {

    function __construct() {
    
		global $gd_dataquery_manager;
		$gd_dataquery_manager->add($this->get_name(), $this);
	}
    
    /**
     * Function to override
     */
    function get_name() {

		return '';
	}
	
	/**
     * Function to override
     */
    function get_noncacheable_page() {

		return null;
	}
	/**
     * Function to override
     */
    function get_cacheable_page() {

		return null;
	}
	/**
     * Function to override
     */
	function get_objectid_fieldname() {

		return 'id';
	}
	/**
     * What fields can be requested on the outside-looking API to query data. By default: everything that must be loaded from the server
     * and that depends on the logged-in user
     */
	function get_allowedfields() {
    
		return array_merge(
			$this->get_nocachefields(),
			$this->get_loggedinuserfields()
		);
	}
	/**
     * What layouts can be requested on the outside-looking API to query data. By default: everything that can be lazy
     */
	function get_allowedlayouts() {
    
		$allowedlayouts = array();
		foreach ($this->get_lazylayouts() as $field => $lazylayouts) {
			foreach ($lazylayouts as $key => $layout) {
				$allowedlayouts[] = $layout;
			}
		}
		return array_unique($allowedlayouts);
	}
	/**
     * Fields whose data must be retrieved each single time from the server. Eg: comment-count, since adding a comment doesn't delete the overall cache,
     * so the number cached in html will be out of date
     */
	function get_nocachefields() {

		return array();
	}
	/**
     * Fields whose data depends on the logged-in user. Eg: editopinionatedvote-url, where the one opinionatedvote has 
     * already been posted by the user
     */
	function get_loggedinuserfields() {

		return array();
	}
	/**
     * Fields whose data is retrieved on a subsequent call to the server
     */
	function get_lazylayouts() {

		return array();
	}
}
	
