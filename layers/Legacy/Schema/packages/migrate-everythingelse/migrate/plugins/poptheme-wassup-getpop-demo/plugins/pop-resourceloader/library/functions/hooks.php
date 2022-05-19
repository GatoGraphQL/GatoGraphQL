<?php

class GetPoPDemo_ResourceLoader_Hooks
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            'PoPTheme_Wassup_ResourceLoaderProcessor_Hooks:css-resources:collapse-hometop',
            $this->getCollapseHometopComponent(...)
        );
    }

    public function getCollapseHometopComponent(?array $component)
    {
        return [GetPoPDemo_Module_Processor_TopLevelCollapseComponents::class, GetPoPDemo_Module_Processor_TopLevelCollapseComponents::COMPONENT_GETPOPDEMO_COLLAPSECOMPONENT_HOMETOP];
    }
}

/**
 * Initialization
 */
new GetPoPDemo_ResourceLoader_Hooks();
