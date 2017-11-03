<?php
class PoP_Frontend_ConversionFileGenerator extends PoP_Engine_FileGeneratorBase {

	function get_dir() {

		return POP_FRONTENDENGINE_BUILD_DIR;
	}

	function get_filename() {

		return 'css-to-style-mapping.json';
	}

	public function generate() {

		// Get all the .css files from all the plugins
		$files = apply_filters('PoP_Frontend_ConversionManager:css-files', array());

		$fileContents = '';
		foreach ($files as $file) {
			$fileContents .= file_get_contents($file).PHP_EOL;
		}

		$class_to_styles = $this->extract($fileContents);

		global $pop_engine_filejsonstorage;
		$pop_engine_filejsonstorage->save($this->get_filepath(), $class_to_styles);
	}

	protected function extract($fileContents) {

		$class_to_styles = array();
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
					$class_to_styles[$selector] .= implode('', $rules);
				}
			}
		}

		return $class_to_styles;
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
global $pop_frontend_conversiongenerator;
$pop_frontend_conversiongenerator = new PoP_Frontend_ConversionFileGenerator();