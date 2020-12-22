<?php
class PoP_MultiDomain_CDN_ConfigFile extends \PoP\FileStore\File\AbstractAccessibleRenderableFile
{
    public function getDir(): string
    {
        return POP_MULTIDOMAIN_CONTENT_DIR;
    }
    public function getUrl(): string
    {
        return POP_MULTIDOMAIN_CONTENT_URL;
    }

    public function getFilename(): string
    {
        return 'multidomain-domainscripts.js';
    }

    // public function getRenderer()
    // {
    //     global $pop_multidomain_initdomainscripts_filerenderer;
    //     return $pop_multidomain_initdomainscripts_filerenderer;
    // }
    /**
     * @return AbstractRenderableFileFragment[]
     */
    protected function getFragmentObjects(): array
    {
        return [
            new PoP_MultiDomain_InitDomainScripts_Config(),
        ];
    }
}

/**
 * Initialize
 */
global $pop_multidomain_initdomainscripts_configfile;
$pop_multidomain_initdomainscripts_configfile = new PoP_MultiDomain_CDN_ConfigFile();
