<?php
namespace PoP\Engine;

abstract class RouteModuleProcessorBase
{
    public function __construct()
    {

        // Comment Leo 30/09/2017: Important: do it at the very end, to make sure that
        // all constants have been set by then (otherwise, in file settingsprocessor.pht
        // it may add the configuration under route "POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS01",
        // it is not treated as false if the constant has not been defined)
        \PoP\CMS\HooksAPI_Factory::getInstance()->addAction(
            'popcms:init', 
            array($this, 'init'), 
            PHP_INT_MAX
        );
    }

    public function init()
    {
        $pop_module_routemoduleprocessor_manager = RouteModuleProcessorManager_Factory::getInstance();
        $pop_module_routemoduleprocessor_manager->add($this);
    }

    /**
     * Function to override
     */
    public function getGroups()
    {
        return array();
    }

    /**
     * Function to override
     */
    public function getModulesVarsPropertiesByNatureAndRoute()
    {
        return array();
    }

    /**
     * Function to override
     */
    public function getModulesVarsPropertiesByNature()
    {
        return array();
    }

    /**
     * Function to override
     */
    public function getModulesVarsProperties()
    {
        return array();
    }
}
