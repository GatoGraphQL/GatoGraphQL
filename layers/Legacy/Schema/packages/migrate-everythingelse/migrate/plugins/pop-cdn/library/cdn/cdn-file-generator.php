<?php
class PoP_CDN_ConfigFile extends \PoP\FileStore\File\AbstractAccessibleRenderableFile
{
    public function getDir(): string
    {
        return POP_CDN_ASSETDESTINATION_DIR;
    }
    public function getUrl(): string
    {
        return POP_CDN_ASSETDESTINATION_URL;
    }

    public function getFilename(): string
    {
        return 'cdn-config.js';
    }

    // public function getRenderer()
    // {
    //     global $pop_cdn_filerenderer;
    //     return $pop_cdn_filerenderer;
    // }
    /**
     * @return AbstractRenderableFileFragment[]
     */
    protected function getFragmentObjects(): array
    {
        return [
            new PoP_CDN_FileReproduction_ThumbprintsConfig(),
        ];
    }
}

/**
 * Initialize
 */
global $pop_cdn_configfile;
$pop_cdn_configfile = new PoP_CDN_ConfigFile();
