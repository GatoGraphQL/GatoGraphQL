<?php

\PoP\Root\App::addFilter('wp_get_attachment_image_attributes', gdWpGetAttachmentImageAttributesRemoveQuotes(...), 10, 2);
function gdWpGetAttachmentImageAttributesRemoveQuotes($attr, $attachment)
{

    // There's a bug in Wordpress: quotes (") don't get escaped properly, so Template Manager stops working...
    // So replace them with the html representation
    $attr['alt'] = str_replace('"', '&#8220;', $attr['alt']);
    return $attr;
}
