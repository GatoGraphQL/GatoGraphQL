<?php
use PoP\FileStore\Facades\JSONFileStoreFacade;
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class PoP_CSSConverter_ConversionFileGenerator
{
    public function generate($file)
    {
        // Get all the .css files from all the plugins
        $cssfiles = \PoP\Root\App::getHookManager()->applyFilters('PoP_CSSConverter_ConversionManager:css-files', array());

        $cssFileContents = '';
        foreach ($cssfiles as $cssfile) {
            $cssFileContents .= file_get_contents($cssfile).PHP_EOL;
        }

        $class_to_styles = $this->extract($cssFileContents);

        JSONFileStoreFacade::getInstance()->save($file, $class_to_styles);
    }

    protected function extract($fileContents)
    {
        /**
         * Validate the Vendor Library
         */
        if (!file_exists(POP_CSSCONVERTER_VENDOR_DIR)) {
            // PHP CSS Parser was not downloaded, then do nothing
            return array();
        }


        /**
         * Load the Vendor Library
         */
        // Comment Leo 10/07/2017: for some reason Composer's autoload doesn't work, so instead we load all the files manually
        // require_once 'vendor/autoload.php';
        include_once dirname(dirname(dirname(__FILE__))).'/vendor-load.php';
        
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
                if ((substr($selector, 0, 1) == '.')  // It must ba a class
                    && (strpos($selector, ':') === false)  // Pseudo
                    && (strpos($selector, '>') === false)  // Child
                    && (strpos($selector, '[') === false)  // Input type
                    && (strpos(substr($selector, 1), '.') === false)  // A concatenation of several classes
                    && (strpos($selector, ' ') === false) // Child
                ) {
                    // If there were rules already defined for that class, then add them
                    $class_to_styles[$selector] .= implode('', $rules);
                }
            }
        }

        return $class_to_styles;
    }
}
    
/**
 * Initialize
 */
global $pop_cssconverter_conversiongenerator;
$pop_cssconverter_conversiongenerator = new PoP_CSSConverter_ConversionFileGenerator();
