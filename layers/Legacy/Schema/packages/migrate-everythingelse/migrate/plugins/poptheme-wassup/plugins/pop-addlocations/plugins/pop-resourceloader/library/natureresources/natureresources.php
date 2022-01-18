<?php

use PoP\Root\Routing\RequestNature;

class PoPThemeWassup_EM_ResourceLoader_Hooks extends PoP_ResourceLoader_NatureResources_ProcessorBase
{
    public function addGenericNatureResources(&$resources, $modulefilter, $options)
    {

        // When processing POP_ADDLOCATIONS_ROUTE_ADDLOCATION, we need a configuration for both target=main and target=modals
        // Because giving no target (the default behaviour) will then choose target=modals, below explicitly create the configuration for target=main
        $nature = RequestNature::GENERIC;
        $ids = array(
            POP_ADDLOCATIONS_ROUTE_ADDLOCATION,
        );
        $merge = false;
        $components = array(
            'format' => POP_FORMAT_MODALS,
            'target' => \PoP\ConfigurationComponentModel\Constants\Targets::MAIN,
        );
        PoP_ResourceLoaderProcessorUtils::addResourcesFromCurrentVars($modulefilter, $resources, $nature, $ids, $merge, $components, $options);
    }
}

/**
 * Initialize
 */
new PoPThemeWassup_EM_ResourceLoader_Hooks();
