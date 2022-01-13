<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class PoPThemeWassup_FileReproduction_BackgroundImage extends PoP_Engine_CSSFileReproductionBase
{
    // public function getRenderer()
    // {
    //     global $popthemewassup_backgroundimage_filerenderer;
    //     return $popthemewassup_backgroundimage_filerenderer;
    // }

    public function getAssetsPath(): string
    {
        return dirname(__FILE__).'/assets/css/background-image.css';
    }

    /**
     * @return mixed[]
     */
    public function getConfiguration(): array
    {
        $configuration = parent::getConfiguration();

        $bgImageArgs = \PoP\Root\App::getHookManager()->applyFilters(
            'PoPThemeWassup_FileReproduction_BackgroundImage:args',
            array(
                'color' => '#ffffff',
                'urls' => array(
                    '1440x900' => '',
                    '1920x1080' => '',
                ),
            )
        );
        $configuration['{{$backgroundColor}}'] = $bgImageArgs['color'];
        foreach ($bgImageArgs['urls'] as $size => $url) {
            $configuration['{{$imageURLs-'.$size.'}}'] = $url;
        }

        return $configuration;
    }
}
