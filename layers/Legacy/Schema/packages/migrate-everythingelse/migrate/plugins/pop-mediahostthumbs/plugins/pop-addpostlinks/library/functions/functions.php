<?php

\PoP\Root\App::getHookManager()->addFilter('getThumbId:default', 'popAddpostlinksDefaultlink', 10, 2);
function popAddpostlinksDefaultlink($thumb_id, $post_id)
{

    // For the links, re-use a different default thumb depending on the domain of the link
    if ($link = PoP_AddPostLinks_Utils::getLink($post_id)) {
        $host = getUrlHost($link);

        // List of $host => $thumb_id
        // Eg: 'guardian.com' => 53433
        $host_thumb_ids = PoP_MediaHostThumbs_Utils::getHostThumbIds();
        if ($host_thumb_id = $host_thumb_ids[$host] ?? null) {
            return $host_thumb_id;
        }
    }

    return $thumb_id;
}
