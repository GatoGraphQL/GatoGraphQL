<?php

// Link to image / Gallery: add "data-size" attr with the dimensions of the pic linked to
// Problem: This will also add the data-size attr when selecting "Custom Url" option, so that the
// link is not actually pointing to the image. But as long as that url is not a link to another internal image,
// which will have its own dimensions, then this is not a serious issue
\PoP\Root\App::addFilter('wp_get_attachment_link', psPopWpGetAttachmentLink(...), 10, 2);
\PoP\Root\App::addFilter('image_send_to_editor', psPopWpGetAttachmentLink(...), 10, 2);
\PoP\Root\App::addFilter('media_send_to_editor', psPopWpGetAttachmentLink(...), 10, 2);
function psPopWpGetAttachmentLink($link, $id)
{

    // Only if it has a link (<a ), because it could also be just the image (<img )
    if (substr($link, 0, 3) == '<a ') {
        $image_meta  = wp_get_attachment_metadata($id);
        if (isset($image_meta['height'], $image_meta['width'])) {
            $size = $image_meta['width'].'x'.$image_meta['height'];
            $link = '<a data-size="'.$size.'" '.substr($link, 3);
        }
    }

    return $link;
}
