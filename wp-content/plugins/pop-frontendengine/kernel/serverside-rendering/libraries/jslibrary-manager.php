<?php
class PoP_ServerSide_JSLibraryManager {

	// All the registered JS libraries
	private $libraries;

	// All the methods that each library can handle. Each method can be served by different libraries
	// This way each library can also serve common methods, like 'destroy', 'getState', 'clearState', etc
	private $methods;

	function __construct() {

		PoP_ServerSide_Libraries_Factory::set_jslibrary_instance($this);
		
		// Initialize internal variables
		$this->libraries = array();
		$this->methods = array();
	}

	function register(&$library, $methods, $highPriority = false, $override = false) {

		$this->libraries[] = $library;
		foreach($methods as $method) {

			// override: allows for any library to override others
			if (!$this->methods[$method] || $override) {
				$this->methods[$method] = array();
			}

			if ($highPriority) {
				array_unshift($this->methods[$method], $library);
			}
			else {
				$this->methods[$method][] = $library;
			}
		}
	}

	function getLibraries($method) {

		return $this->methods[$method] ?? array();
	}

	function execute($method, &$args) {

		// Call 'destroy' from all libraries in popJSLibraryManager
		$ret = array();
		$libraries = $this->methods[$method];
		if ($libraries) {
			for ($index = 0; $index<count($libraries); $index++) {

				$library = $libraries[$index];
				$ret['l'.$index] = $library->$method($args);
			}
		}

		return $ret;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_ServerSide_JSLibraryManager();