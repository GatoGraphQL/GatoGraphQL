<?php
 
class GD_DataQuery {

	// Allow Plugins to inject extra properties. Eg: PoP User Login can inject loggedinuser-fields
	protected $properties;

    function __construct() {

    	$this->properties = array();
    
		global $gd_dataquery_manager;
		$gd_dataquery_manager->add($this->get_name(), $this);
	}
    
    /**
     * Function to override
     */
    function get_name() {

		return '';
	}

	function add_property($name, $value) {

		$this->properties[$name] = array_merge(
			$this->properties[$name] ?? array(),
			$value
		);
	}

	function get_property($name) {

		return $this->properties[$name];
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
    
		$allowedfields = $this->get_nocachefields();
		return apply_filters('Dataquery:'.$this->get_name().':allowedfields', $allowedfields);
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
		return apply_filters('Dataquery:'.$this->get_name().':allowedlayouts', array_unique($allowedlayouts));
	}
	/**
     * Fields whose data must be retrieved each single time from the server. Eg: comment-count, since adding a comment doesn't delete the overall cache,
     * so the number cached in html will be out of date
     */
	function get_nocachefields() {

		return apply_filters('Dataquery:'.$this->get_name().':nocachefields', array());
	}
	/**
     * Fields whose data is retrieved on a subsequent call to the server
     */
	function get_lazylayouts() {

		return apply_filters('Dataquery:'.$this->get_name().':lazylayouts', array());
	}
}
	
