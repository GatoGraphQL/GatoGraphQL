<?php
 
class GD_DataQuery_HookBase {

	function __construct() {

		$name = $this->get_dataquery_name();
		add_filter('Dataquery:'.$name.':allowedfields', array($this, 'add_allowedfields'));
		add_filter('Dataquery:'.$name.':allowedlayouts', array($this, 'add_allowedlayouts'));
		add_filter('Dataquery:'.$name.':nocachefields', array($this, 'add_nocachefields'));
		add_filter('Dataquery:'.$name.':lazylayouts', array($this, 'add_lazylayouts'));
	}
	function get_dataquery_name() {

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
	function add_lazylayouts($lazylayouts) {

		return array_merge(
			$lazylayouts,
			$this->get_lazylayouts()
		);
	}

	function get_allowedfields() {

		return $this->get_nocachefields();
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
	

	/**
	 * Implement functions below in the hook implementation
	 */
	function get_nocachefields() {

		return array();
	}
	function get_lazylayouts() {

		return array();
	}
}