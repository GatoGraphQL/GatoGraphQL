<?php

\PoP\Root\App::getHookManager()->addFilter('getThumbId:default', 'popContentpostlinksDefaultlink', 10, 2);
function popContentpostlinksDefaultlink($thumb_id, $post_id)
{

    // For the links, re-use a different default thumb depending on the domain of the link
    if (PoP_ContentPostLinks_Utils::isLink($post_id)) {
        $host = PoP_ContentPostLinks_Utils::getLinkHost($post_id);

        // List of $host => $thumb_id
        // Eg: 'guardian.com' => 53433
        $host_thumb_ids = PoP_MediaHostThumbs_Utils::getHostThumbIds();
        if ($host_thumb_id = $host_thumb_ids[$host] ?? null) {
            return $host_thumb_id;
        }
    }

    return $thumb_id;
}
