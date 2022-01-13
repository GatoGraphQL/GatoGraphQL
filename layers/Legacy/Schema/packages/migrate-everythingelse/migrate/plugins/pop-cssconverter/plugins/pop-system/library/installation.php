<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
class PoP_CSSConverter_Installation
{
    public function __construct()
    {
        HooksAPIFacade::getInstance()->addAction('PoP:system-build', array($this, 'systemBuild'));
    }

    public function systemBuild()
    {
        global $pop_cssconverter_conversionfile, $pop_cssconverter_conversiongenerator;

        // CSS to Styles: generate the database
        $pop_cssconverter_conversiongenerator->generate($pop_cssconverter_conversionfile);
    }
}

/**
 * Initialization
 */
new PoP_CSSConverter_Installation();
