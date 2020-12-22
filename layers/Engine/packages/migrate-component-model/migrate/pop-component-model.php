<?php
/*
Plugin Name: PoP Component Model
Version: 0.1
Description: The Platform of Platforms is a niche Social Media website. It can operate as a Platform, it can aggregate other Platforms, or it can be a combination.
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/
namespace PoP\ComponentModel;
use PoP\Hooks\Facades\HooksAPIFacade;

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_COMPONENTMODEL_VERSION', 0.108);
define('POP_COMPONENTMODEL_DIR', dirname(__FILE__));
define('POP_COMPONENTMODEL_TEMPLATES', POP_COMPONENTMODEL_DIR.'/templates');

class Plugin
{
    public function __construct()
    {
        // Allow the Theme to override definitions.
        // Priority: new section, after PoP CMS section
        HooksAPIFacade::getInstance()->addAction('plugins_loaded', array($this, 'init'), 22);
    }
    public function init()
    {
        if ($this->validate()) {
            $this->initialize();
            define('POP_COMPONENTMODEL_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new Plugin();
