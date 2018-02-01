<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
class PoPThemeWassup_FileReproduction_FeedThumb extends PoP_Engine_CSSFileReproductionBase {

    function get_renderer() {

        global $popthemewassup_feedthumb_filerenderer;
        return $popthemewassup_feedthumb_filerenderer;
    }

    public function get_assets_path() {
        
        return dirname(__FILE__).'/assets/css/feed-thumb.css';
    }

    public function get_configuration() {
        
        $configuration = parent::get_configuration();

        $img = wp_get_attachment_image_src(POPTHEME_WASSUP_IMAGE_FEEDBACKGROUND, 'thumb-feed');
        $configuration['{{$backgroundImageURL}}'] = $img[0];
        $configuration['{{$backgroundImageWidth}}'] = $img[1];
        $configuration['{{$backgroundImageHeight}}'] = $img[2];

        return $configuration;
    }
}
    
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPThemeWassup_FileReproduction_FeedThumb();
