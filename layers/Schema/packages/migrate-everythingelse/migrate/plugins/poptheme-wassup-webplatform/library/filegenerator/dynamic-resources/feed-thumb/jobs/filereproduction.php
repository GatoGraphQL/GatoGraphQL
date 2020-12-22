<?php

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

        $cmsmediaapi = \PoPSchema\Media\FunctionAPIFactory::getInstance();
        $img = $cmsmediaapi->getMediaSrc(POPTHEME_WASSUP_IMAGE_FEEDBACKGROUND, 'thumb-feed');
        $configuration['{{$backgroundImageURL}}'] = $img[0];
        $configuration['{{$backgroundImageWidth}}'] = $img[1];
        $configuration['{{$backgroundImageHeight}}'] = $img[2];

        return $configuration;
    }
}
