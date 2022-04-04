<?php

use PoP\ComponentModel\Misc\GeneralUtils;

// Add the path to the post as a param in the URL, so the ResourceLoader knows what resources to load when previewing a post
\PoP\Root\App::addFilter('ppp_preview_link', popPppResourceloaderPreviewLink(...), 10, 3);
function popPppResourceloaderPreviewLink($link, $post_id, $post)
{
    return GeneralUtils::addQueryArgs([
    	POP_PARAMS_PATH => GeneralUtils::maybeAddTrailingSlash(\PoPCMSSchema\Posts\Engine_Utils::getCustomPostPath($post_id, true)),
    ], $link);
}
