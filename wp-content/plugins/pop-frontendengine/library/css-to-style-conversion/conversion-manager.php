<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Core (reusable) functions of the website
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_Frontend_ConversionManager {

	public $class_to_styles, $initialized;

	function __construct() {

		$this->class_to_styles = array();
		$this->initialized = false;
	}

	function init() {

		// Allows lazy init
		if (!$this->initialized) {

			$this->initialized = true;

			// Get the inner variable from the cache, if it exists
			global $pop_frontend_conversionstorage;
			$this->class_to_styles = $pop_frontend_conversionstorage->get();
		}
	}

	public function generate() {

		// Reset the inner variable to empty, to generate it once again
		$this->class_to_styles = array();

		// Get all the .css files from all the plugins
		$files = apply_filters('PoP_Frontend_ConversionManager:css-files', array());

		$fileContents = '';
		foreach ($files as $file) {
			$fileContents .= file_get_contents($file).PHP_EOL;
		}

		$this->extract($fileContents);

		global $pop_frontend_conversionstorage;
		$pop_frontend_conversionstorage->save($this->class_to_styles);
	}

	protected function extract($fileContents) {

		$oCssParser = new Sabberworm\CSS\Parser($fileContents);
		$oCssDocument = $oCssParser->parse();
		foreach ($oCssDocument->getAllDeclarationBlocks() as $oBlock) {

			// Obtain all the rules for the declaration block
			$rules = array();
			foreach ($oBlock->getRules() as $rule) {

				$rules[] = $rule->__toString();
			}
			
			// Set all the rules for that selector
			foreach ($oBlock->getSelectors() as $oSelector) {

				// Only add the rules for classes, and of a single level (no children or pseudo-selectors)
				$selector = $oSelector->getSelector();
				if (
					(substr($selector, 0, 1) == '.') && // It must ba a class
					(strpos($selector, ':') === false) && // Pseudo
					(strpos($selector, '>') === false) && // Child
					(strpos($selector, '[') === false) && // Input type
					(strpos(substr($selector, 1), '.') === false) && // A concatenation of several classes
					(strpos($selector, ' ') === false) // Child
					) {
					
					// If there were rules already defined for that class, then add them
					$this->class_to_styles[$selector] .= implode('', $rules);
				}
			}
		}
	}

	public function get_styles_from_classes($classes) {

		// Lazy init
		$this->init();
		
		// Add a dot to all classes, to convert them into a CSS selector
		// $classes = array_map(array('PoP_Frontend_ConversionManager', 'get_class_selector'), $classes);
		$classes = array_map(array($this, 'get_class_selector'), $classes);
	
		// Obtain the styles
		// $intersected = array_intersect(PoP_Frontend_ConversionUtils::get_classes(), $classes);
		$intersected = array_intersect($this->get_classes(), $classes);
		
		// return array_map(array('PoP_Frontend_ConversionUtils', 'convert'), $intersected);
		return array_map(array($this, 'convert'), $intersected);
	}

	public function get_classes() {

		return array_keys($this->class_to_styles);
	}

	public function convert($class) {

		return $this->class_to_styles[$class];
	}

	public function get_class_selector($classname) {

		return '.'.$classname;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $pop_frontend_conversionmanager;
$pop_frontend_conversionmanager = new PoP_Frontend_ConversionManager();