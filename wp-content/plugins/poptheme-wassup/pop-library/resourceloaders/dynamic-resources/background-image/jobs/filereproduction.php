<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
class PoPThemeWassup_FileReproduction_BackgroundImage extends PoP_Engine_CSSFileReproductionBase {

    function get_renderer() {

        global $popthemewassup_backgroundimage_filerenderer;
        return $popthemewassup_backgroundimage_filerenderer;
    }

    public function get_assets_path() {
        
        return dirname(__FILE__).'/assets/css/background-image.css';
    }

    public function get_configuration() {
        
        $configuration = parent::get_configuration();

        $bgImageProps = apply_filters(
            'PoPThemeWassup_FileReproduction_BackgroundImage:props',
            array(
                'color' => '#ffffff',
                'urls' => array(
                    '1440x900' => '',
                    '1920x1080' => '',
                ),
            )
        );
        $configuration['{{$backgroundColor}}'] = $bgImageProps['color'];
        foreach ($bgImageProps['urls'] as $size => $url) {
            $configuration['{{$imageURLs-'.$size.'}}'] = $url;
        }

        return $configuration;
    }
}
    
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPThemeWassup_FileReproduction_BackgroundImage();
