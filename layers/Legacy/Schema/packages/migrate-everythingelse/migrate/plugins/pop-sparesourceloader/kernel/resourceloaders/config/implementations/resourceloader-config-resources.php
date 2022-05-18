<?php

class PoP_SPAResourceLoader_FileReproduction_ResourcesConfig extends PoP_SPAResourceLoader_FileReproduction_ResourcesConfigBase
{
    /**
     * @return mixed[]
     */
    public function getConfiguration(): array
    {
        $configuration = parent::getConfiguration();

        // The assets that must be included in the body must also be added to the types/sources,
        // So that the generated resources.js file, containing all resources, does not override these values in popSPAResourceLoader.config
        // as already set in `initScripts` in pop-sparesourceloader/kernel/functions/engine-initialization-hooks.php
        if (PoP_ResourceLoader_ServerUtils::includeResourcesInBody()) {
            if ($dynamic_component_resources_data = PoPWebPlatform_ResourceLoader_ScriptsAndStylesUtils::getDynamicModuleResourcesData()) {
                $dynamic_component_resourcesources = $dynamic_component_resources_data['sources'];
                $dynamic_component_resourcetypes = $dynamic_component_resources_data['types'];

                $configuration['$sources'] = array_unique(
                    array_merge(
                        $configuration['$sources'],
                        $dynamic_component_resourcesources
                    )
                );

                foreach ($dynamic_component_resourcetypes as $resourcetype => $resourcetype_resources) {
                    $configuration['$types'][$resourcetype] = array_unique(
                        array_merge(
                            $configuration['$types'][$resourcetype],
                            $resourcetype_resources
                        )
                    );
                }
            }
        }

        return $configuration;
    }

    // public function getRenderer()
    // {
    //     global $pop_sparesourceloader_resources_configfile_renderer;
    //     return $pop_sparesourceloader_resources_configfile_renderer;
    // }

    public function getAssetsPath(): string
    {
        return POP_SPARESOURCELOADER_ASSETS_DIR.'/js/jobs/resourceloader-config-setresources.js';
    }
}
