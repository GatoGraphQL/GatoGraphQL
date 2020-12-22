<?php
class PoP_SPAResourceLoader_ConfigFile extends PoP_SPAResourceLoader_ConfigFileBase
{
    public function getFilename(): string
    {
        return 'config.js';
    }

    // public function getRenderer()
    // {
    //     global $pop_sparesourceloader_configfile_renderer;
    //     return $pop_sparesourceloader_configfile_renderer;
    // }
    /**
     * @return AbstractRenderableFileFragment[]
     */
    protected function getFragmentObjects(): array
    {
        return [
            new PoP_SPAResourceLoader_FileReproduction_Config(),
        ];
    }
}

/**
 * Initialize
 */
global $pop_sparesourceloader_configfile;
$pop_sparesourceloader_configfile = new PoP_SPAResourceLoader_ConfigFile();
