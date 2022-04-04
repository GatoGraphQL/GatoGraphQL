<?php
/*
Plugin Name: PoP Engine HTML/CSS Platform
Version: 0.1
Description: Front-end module for the Platform of Platforms
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_ENGINEHTMLCSSPLATFORM_VERSION', 0.160);
define('POP_ENGINEHTMLCSSPLATFORM_DIR', dirname(__FILE__));
define('POP_ENGINEHTMLCSSPLATFORM_TEMPLATES', POP_ENGINEHTMLCSSPLATFORM_DIR.'/templates');

class PoPHTMLCSSPlatform
{
    public function __construct()
    {

        // Priority: new section, after PoP Application section
        \PoP\Root\App::addAction(
            'plugins_loaded',
            function() {
                if ($this->validate()) {
                    require_once 'platforms/load.php';
                }
            },
            392
        );
        \PoP\Root\App::addAction('plugins_loaded', $this->init(...), 888396);
    }
    public function init()
    {
        // Allow other plug-ins to modify the plugins_url path (eg: pop-aws adding the CDN)
        define('POP_ENGINEHTMLCSSPLATFORM_URL', plugins_url('', __FILE__));

        if ($this->validate()) {

            $this->initialize();
            define('POP_ENGINEHTMLCSSPLATFORM_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoPHTMLCSSPlatform_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        global $PoPHTMLCSSPlatform_Initialization;
        return $PoPHTMLCSSPlatform_Initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoPHTMLCSSPlatform();
