<?php
class PoP_SPAResourceLoader_ConfigInitialResourcesFile extends PoP_SPAResourceLoader_ConfigAddResourcesFileBase
{
    public function getFilename(): string
    {
        return 'initialresources.js';
    }

    // public function getRenderer()
    // {
    //     global $pop_sparesourceloader_initialresources_configfile_renderer;
    //     return $pop_sparesourceloader_initialresources_configfile_renderer;
    // }
    /**
     * @return AbstractRenderableFileFragment[]
     */
    protected function getFragmentObjects(): array
    {
        return [
            new PoP_SPAResourceLoader_FileReproduction_InitialResourcesConfig(),
        ];
    }
}

/**
 * Initialize
 */
global $pop_sparesourceloader_initialresources_configfile;
$pop_sparesourceloader_initialresources_configfile = new PoP_SPAResourceLoader_ConfigInitialResourcesFile();
