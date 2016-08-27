<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
class GD_DataQuery_HookBase {

	function __construct() {

		$header = $this->get_filter_header();
		add_filter($header.':allowedfields', array($this, 'add_allowedfields'));
		add_filter($header.':allowedlayouts', array($this, 'add_allowedlayouts'));
		add_filter($header.':nocachefields', array($this, 'add_nocachefields'));
		add_filter($header.':lazylayouts', array($this, 'add_lazylayouts'));
		add_filter($header.':loggedinuserfields', array($this, 'add_loggedinuserfields'));
	}
	function get_filter_header() {

		return '';
	}
	function add_allowedfields($allowedfields) {

		return array_unique(
			array_merge(
				$allowedfields,
				$this->get_allowedfields()
			)
		);
	}
	function add_allowedlayouts($allowedlayouts) {

		return array_unique(
			array_merge(
				$allowedlayouts,
				$this->get_allowedlayouts()
			)
		);
	}
	function add_nocachefields($nocachefields) {

		return array_merge(
			$nocachefields,
			$this->get_nocachefields()
		);
	}
	function add_loggedinuserfields($loggedinuserfields) {

		return array_merge(
			$loggedinuserfields,
			$this->get_loggedinuserfields()
		);
	}
	function add_lazylayouts($lazylayouts) {

		return array_merge(
			$lazylayouts,
			$this->get_lazylayouts()
		);
	}
	function get_allowedfields() {

		return array_merge(
			$this->get_nocachefields(),
			$this->get_loggedinuserfields()
		);
	}
	function get_allowedlayouts() {

		$allowedlayouts = array();
		foreach ($this->get_lazylayouts() as $field => $lazylayouts) {
			foreach ($lazylayouts as $key => $layout) {
				$allowedlayouts[] = $layout;
			}
		}
		return array_unique($allowedlayouts);
	}
	function get_nocachefields() {

		return array();
	}
	function get_loggedinuserfields() {

		return array();
	}
	function get_lazylayouts() {

		return array();
	}
}