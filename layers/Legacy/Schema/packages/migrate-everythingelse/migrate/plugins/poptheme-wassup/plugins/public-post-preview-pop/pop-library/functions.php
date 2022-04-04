<?php

// The target where to add the preview is the Quickview
\PoP\Root\App::addFilter('gd_ppp_previewurl_target', popthemewassupPppPreviewurlTarget(...));
function popthemewassupPppPreviewurlTarget($target)
{
    return POP_TARGET_QUICKVIEW;
}
