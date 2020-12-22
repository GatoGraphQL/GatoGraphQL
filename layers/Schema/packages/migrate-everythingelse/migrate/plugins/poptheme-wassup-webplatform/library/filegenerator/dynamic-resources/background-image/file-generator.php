<?php
class PoPThemeWassup_BackgroundImage_File extends \PoP\FileStore\File\AbstractAccessibleRenderableFile
{
    public function getDir(): string
    {
        return POPTHEME_WASSUPWEBPLATFORM_THEMECUSTOMIZATION_ASSETDESTINATION_DIR;
    }
    public function getUrl(): string
    {
        return POPTHEME_WASSUPWEBPLATFORM_THEMECUSTOMIZATION_ASSETDESTINATION_URL;
    }

    public function getFilename(): string
    {
        return 'background-image.css';
    }

    // public function getRenderer()
    // {
    //     global $popthemewassup_backgroundimage_filerenderer;
    //     return $popthemewassup_backgroundimage_filerenderer;
    // }
    /**
     * @return AbstractRenderableFileFragment[]
     */
    protected function getFragmentObjects(): array
    {
        return [
            new PoPThemeWassup_FileReproduction_BackgroundImage(),
        ];
    }
}

/**
 * Initialize
 */
global $popthemewassup_backgroundimage_file;
$popthemewassup_backgroundimage_file = new PoPThemeWassup_BackgroundImage_File();
