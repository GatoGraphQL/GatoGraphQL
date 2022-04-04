<?php
use PoP\FileStore\Facades\JSONFileStoreFacade;

class PoP_CSSConverter_ConversionManager
{
    public $class_to_styles;
    public $initialized;

    public function __construct()
    {
        $this->class_to_styles = array();
        $this->initialized = false;
    }

    public function init()
    {

        // Allows lazy init
        if (!$this->initialized) {
            $this->initialized = true;

            // Get the inner variable from the cache, if it exists
            global $pop_cssconverter_conversionfile;
            $this->class_to_styles = JSONFileStoreFacade::getInstance()->get($pop_cssconverter_conversionfile);
        }
    }

    public function getStylesFromClasses($classes)
    {

        // Lazy init
        $this->init();
        
        // Add a dot to all classes, to convert them into a CSS selector
        $classes = array_map($this->getClassSelector(...), $classes);
    
        // Obtain the styles
        $intersected = array_values(array_intersect($this->getClasses(), $classes));
        
        return array_map($this->convert(...), $intersected);
    }

    public function getClasses()
    {
        return array_keys($this->class_to_styles);
    }

    public function convert($class)
    {
        return $this->class_to_styles[$class];
    }

    public function getClassSelector($classname)
    {
        return '.'.$classname;
    }
}

/**
 * Initialization
 */
global $pop_cssconverter_conversionmanager;
$pop_cssconverter_conversionmanager = new PoP_CSSConverter_ConversionManager();
