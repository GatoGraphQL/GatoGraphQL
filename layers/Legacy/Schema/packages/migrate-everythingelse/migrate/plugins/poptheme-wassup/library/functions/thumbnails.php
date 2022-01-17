<?php
use PoPCMSSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;

\PoP\Root\App::addAction('after_setup_theme', 'gdThumbEnable');
function gdThumbEnable()
{
    add_theme_support('post-thumbnails');
}


function gdCustomThumbSizes()
{

    // add_image_size('thumb-lg', 620, 360, true);
    // Used for the Gallery, no cropping. Width must be 480px, height we don't care so much
    add_image_size('thumb-pagewide', 480, 3000);

    // Below two thumbs have the same proportions
    add_image_size('thumb-feed', 480, 267, true);
    add_image_size('thumb-md', 360, 200, true);

    add_image_size('thumb-sm', 75, 60, true);
    add_image_size('thumb-xs', 40, 40, true);
    add_image_size('favicon', 16, 16, true);
}
gdCustomThumbSizes();


/**
 * Default thumbs
 */
\PoP\Root\App::addFilter('getThumbId:default', 'gdThumbDefault', 10, 2);
function gdThumbDefault($thumb_id, $post_id)
{
    $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
    if ($customPostTypeAPI->getCustomPostType($post_id) == POP_ADDHIGHLIGHTS_POSTTYPE_HIGHLIGHT && POPTHEME_WASSUP_IMAGE_NOFEATUREDIMAGEHIGHLIGHTPOST) {
        return POPTHEME_WASSUP_IMAGE_NOFEATUREDIMAGEHIGHLIGHTPOST;
    } elseif (POPTHEME_WASSUP_IMAGE_NOFEATUREDIMAGEPOST) {
        return POPTHEME_WASSUP_IMAGE_NOFEATUREDIMAGEPOST;
    }

    return $thumb_id;
}
