<?php
class PoPThemeWassup_FeedThumb_File extends \PoP\FileStore\File\AbstractAccessibleRenderableFile
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
        return 'feed-thumb.css';
    }

    // public function getRenderer()
    // {
    //     global $popthemewassup_feedthumb_filerenderer;
    //     return $popthemewassup_feedthumb_filerenderer;
    // }
    /**
     * @return AbstractRenderableFileFragment[]
     */
    protected function getFragmentObjects(): array
    {
        return [
            new PoPThemeWassup_FileReproduction_FeedThumb(),
        ];
    }
}

/**
 * Initialize
 */
global $popthemewassup_feedthumb_file;
$popthemewassup_feedthumb_file = new PoPThemeWassup_FeedThumb_File();
