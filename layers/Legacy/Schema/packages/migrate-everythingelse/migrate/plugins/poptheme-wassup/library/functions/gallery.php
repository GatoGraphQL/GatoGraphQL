<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

// // Override what sizes can be chosen for the Gallery: this was added using a Change PoP
// \PoP\Root\App::getHookManager()->addFilter('popGalleryimageSizeNamesChoose', 'popGalleryimageSizeNamesChoose');
// function popGalleryimageSizeNamesChoose($sizes) {

//     // Remove all other sizes, only keep below 2 which have a perfect fit for a Gallery
//     return array(
//         'thumb-pagewide' => TranslationAPIFacade::getInstance()->__('Wide', 'poptheme-wassup'),
//         'thumbnail' => TranslationAPIFacade::getInstance()->__('Thumbnail', 'poptheme-wassup'),
//     );
// }

// // Add the thumb-pagewide image size everywhere
// \PoP\Root\App::getHookManager()->addFilter('image_size_names_choose', 'popImageSizeNamesChoose', 1000);
// function popImageSizeNamesChoose($sizes) {

//     $sizes['thumb-pagewide'] = TranslationAPIFacade::getInstance()->__('Wide', 'poptheme-wassup');
//     return $sizes;
//     // return array(
//     //     'thumb-pagewide' => TranslationAPIFacade::getInstance()->__('Wide', 'poptheme-wassup'),
//     //     'thumbnail' => TranslationAPIFacade::getInstance()->__('Thumbnail', 'poptheme-wassup'),
//     // );
// }

// Override what sizes can be chosen for the Gallery
\PoP\Root\App::getHookManager()->addFilter('image_size_names_choose', 'popImageSizeNamesChoose', 1000);
function popImageSizeNamesChoose($sizes)
{
    if (is_admin()) {
        // In the back-end, add the thumb-pagewide to the list of options
        $sizes['thumb-pagewide'] = TranslationAPIFacade::getInstance()->__('Wide', 'poptheme-wassup');
        return $sizes;
    }

    // In the front-end, limit the user what sizes can be selected, to fit our layout perfectly
    return array(
        'full'      => TranslationAPIFacade::getInstance()->__('Full Size'),
        'thumb-pagewide' => TranslationAPIFacade::getInstance()->__('Wide', 'poptheme-wassup'),
        'thumbnail' => TranslationAPIFacade::getInstance()->__('Thumbnail', 'poptheme-wassup'),
    );
}
\PoP\Root\App::getHookManager()->addFilter('media_view_settings', 'popMediaViewSettingsDefaultthumb');
function popMediaViewSettingsDefaultthumb($settings)
{

    // Change from thumbnail to the thumb-pagewide size
    $settings['galleryDefaults']['size'] = 'thumb-pagewide';
    return $settings;
}

// // Override the default WP Post Gallery (function galleryShortcode( $attr ) in wp-includes/media.php)
// \PoP\Root\App::getHookManager()->addFilter('post_gallery', 'gdPostGallery', 10, 2);
// function gdPostGallery($output, $attr) {

//     // From here onwards, copied from media.php, and made needed customizations

//     // We're trusting author input, so let's at least make sure it looks like a valid orderby statement
//     if ( isset( $attr['orderby'] ) ) {
//         $attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
//         if ( !$attr['orderby'] )
//             unset( $attr['orderby'] );
//     }

//     $html5 = current_theme_supports( 'html5', 'gallery' );
//     extract(shortcode_props(array(
//         'order'      => 'ASC',
//         'orderby'    => 'menu_order ID',
//         'id'         => $post ? $post->ID : 0,
//         'itemtag'    => $html5 ? 'figure'     : 'dl',
//         'icontag'    => $html5 ? 'div'        : 'dt',
//         'captiontag' => $html5 ? 'figcaption' : 'dd',
//         'columns'    => 3,
//         // Change PoP: change the default size to "thumb-pagewide"
//         // 'size'       => 'thumbnail',
//         'size'       => 'thumb-pagewide',
//         'include'    => '',
//         'exclude'    => '',
//         'link'       => ''
//     ), $attr, 'gallery'));

//     $id = intval($id);
//     if ( 'RAND' == $order )
//         $orderby = 'none';

//     if ( !empty($include) ) {
            // $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
//         $_attachments = $customPostTypeAPI->getCustomPosts( array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );

//         $attachments = array();
//         foreach ( $_attachments as $key => $val ) {
//             $attachments[$val->ID] = $_attachments[$key];
//         }
//     } elseif ( !empty($exclude) ) {
//         $attachments = get_children( array('post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
//     } else {
//         $attachments = get_children( array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
//     }

//     if ( empty($attachments) )
//         return '';

//     if ( is_feed() ) {
//         $output = "\n";
//         foreach ( $attachments as $att_id => $attachment )
//             $output .= wp_get_attachment_link($att_id, $size, true) . "\n";
//         return $output;
//     }

//     $itemtag = tag_escape($itemtag);
//     $captiontag = tag_escape($captiontag);
//     $icontag = tag_escape($icontag);
//     $valid_tags = wp_kses_allowed_html( 'post' );
//     if ( ! isset( $valid_tags[ $itemtag ] ) )
//         $itemtag = 'dl';
//     if ( ! isset( $valid_tags[ $captiontag ] ) )
//         $captiontag = 'dd';
//     if ( ! isset( $valid_tags[ $icontag ] ) )
//         $icontag = 'dt';

//     $columns = intval($columns);
//     $itemwidth = $columns > 0 ? floor(100/$columns) : 100;
//     $float = is_rtl() ? 'right' : 'left';

//     $selector = "gallery-{$instance}";

//     $gallery_style = $gallery_div = '';

//     *
//      * Filter whether to print default gallery styles.
//      *
//      * @since 3.1.0
//      *
//      * @param bool $print Whether to print default gallery styles.
//      *                    Defaults to false if the theme supports HTML5 galleries.
//      *                    Otherwise, defaults to true.

//     // Change PoP: commented all code below, no need to override anything
//     // Instead, use the following gallery_div:
//     $size_class = sanitize_html_class( $size );
//     $output = "<div class='gallery gallery-size-{$size_class}'>";
//     /*
//     if ( \PoP\Root\App::getHookManager()->applyFilters( 'use_default_gallery_style', ! $html5 ) ) {
//         $gallery_style = "
//         <style type='text/css'>
//             #{$selector} {
//                 margin: auto;
//             }
//             #{$selector} .gallery-item {
//                 float: {$float};
//                 margin-top: 10px;
//                 text-align: center;
//                 width: {$itemwidth}%;
//             }
//             #{$selector} img {
//                 border: 2px solid #cfcfcf;
//             }
//             #{$selector} .gallery-caption {
//                 margin-left: 0;
//             }
//             / * see galleryShortcode() in wp-includes/media.php * /
//         </style>\n\t\t";
//     }

//     $size_class = sanitize_html_class( $size );
//     $gallery_div = "<div id='$selector' class='gallery galleryid-{$id} gallery-columns-{$columns} gallery-size-{$size_class}'>";

//     / **
//      * Filter the default gallery shortcode CSS styles.
//      *
//      * @since 2.5.0
//      *
//      * @param string $gallery_style Default gallery shortcode CSS styles.
//      * @param string $gallery_div   Opening HTML div container for the gallery shortcode output.
//      * /
//     $output = \PoP\Root\App::getHookManager()->applyFilters( 'gallery_style', $gallery_style . $gallery_div );
//     */

//     $i = 0;
//     foreach ( $attachments as $id => $attachment ) {
//         // Change PoP: always add the link to the file
//         // All lines below commented
//         // if ( ! empty( $link ) && 'file' === $link )
//             $image_output = wp_get_attachment_link( $id, $size, false, false );
//         // elseif ( ! empty( $link ) && 'none' === $link )
//         //     $image_output = wp_get_attachment_image( $id, $size, false );
//         // else
//         //     $image_output = wp_get_attachment_link( $id, $size, true, false );

//         $image_meta  = wp_get_attachment_metadata( $id );

//         $orientation = '';
//         if ( isset( $image_meta['height'], $image_meta['width'] ) )
//             $orientation = ( $image_meta['height'] > $image_meta['width'] ) ? 'portrait' : 'landscape';

//         $output .= "<{$itemtag} class='gallery-item'>";
//         $output .= "
//             <{$icontag} class='gallery-icon {$orientation}'>
//                 $image_output
//             </{$icontag}>";
//         if ( $captiontag && trim($attachment->post_excerpt) ) {
//             $output .= "
//                 <{$captiontag} class='wp-caption-text gallery-caption'>
//                 " . wptexturize($attachment->post_excerpt) . "
//                 </{$captiontag}>";
//         }
//         $output .= "</{$itemtag}>";

//         // Change PoP: no need to add the <br/>, we want all images all fitting in a row, adjusted by screen size
//         // Lines below commented
//         // if ( ! $html5 && $columns > 0 && ++$i % $columns == 0 ) {
//         //     $output .= '<br style="clear: both" />';
//         // }
//     }

//     // Change PoP: no need to add the <br/>, we want all images all fitting in a row, adjusted by screen size
//     // Lines below commented
//     // if ( ! $html5 && $columns > 0 && $i % $columns !== 0 ) {
//     //     $output .= "
//     //         <br style='clear: both' />";
//     // }

//     $output .= "
//         </div>\n";

//     return $output;
// }
