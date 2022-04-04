<?php

class PoPTheme_Wassup_Bootstrap_ResourceLoaderProcessor_Hooks
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            'PoP_CoreProcessors_Bootstrap_ResourceLoaderProcessor:dependencies:multiselect',
            $this->getMultiselectDependencies(...)
        );
    }

    public function getMultiselectDependencies($dependencies)
    {
        $dependencies[] = [PoPTheme_Wassup_Core_Bootstrap_CSSResourceLoaderProcessor::class, PoPTheme_Wassup_Core_Bootstrap_CSSResourceLoaderProcessor::RESOURCE_CSS_MULTISELECT];
        return $dependencies;
    }
}

/**
 * Initialization
 */
new PoPTheme_Wassup_Bootstrap_ResourceLoaderProcessor_Hooks();
