<?php

use PoPCMSSchema\Media\Facades\MediaTypeAPIFacade;

class PoPThemeWassup_FileReproduction_FeedThumb extends PoP_Engine_CSSFileReproductionBase
{
    // public function getRenderer()
    // {
    //     global $popthemewassup_feedthumb_filerenderer;
    //     return $popthemewassup_feedthumb_filerenderer;
    // }

    public function getAssetsPath(): string
    {
        return dirname(__FILE__).'/assets/css/feed-thumb.css';
    }

    /**
     * @return mixed[]
     */
    public function getConfiguration(): array
    {
        $configuration = parent::getConfiguration();

        $mediaTypeAPI = MediaTypeAPIFacade::getInstance();
        $img = $mediaTypeAPI->getImageProperties(POPTHEME_WASSUP_IMAGE_FEEDBACKGROUND, 'thumb-feed');
        $configuration['{{$backgroundImageURL}}'] = $img['src'];
        $configuration['{{$backgroundImageWidth}}'] = $img['width'];
        $configuration['{{$backgroundImageHeight}}'] = $img['height'];

        return $configuration;
    }
}
