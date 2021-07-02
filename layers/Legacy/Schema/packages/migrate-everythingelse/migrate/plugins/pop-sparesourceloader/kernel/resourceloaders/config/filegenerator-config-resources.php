<?php
class PoP_SPAResourceLoader_ConfigResourcesFile extends PoP_SPAResourceLoader_ConfigFileBase
{
    public function getFilename(): string
    {
        return 'resources.js';
    }

    // public function getRenderer()
    // {
    //     global $pop_sparesourceloader_resources_configfile_renderer;
    //     return $pop_sparesourceloader_resources_configfile_renderer;
    // }
    /**
     * @return AbstractRenderableFileFragment[]
     */
    protected function getFragmentObjects(): array
    {
        return [
            new PoP_SPAResourceLoader_FileReproduction_ResourcesConfig(),
        ];
    }
}

/**
 * Initialize
 */
global $pop_sparesourceloader_resources_configfile;
$pop_sparesourceloader_resources_configfile = new PoP_SPAResourceLoader_ConfigResourcesFile();
