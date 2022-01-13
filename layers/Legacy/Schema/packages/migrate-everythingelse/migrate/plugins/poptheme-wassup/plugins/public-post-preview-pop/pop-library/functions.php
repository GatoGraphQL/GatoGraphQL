<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

// The target where to add the preview is the Quickview
\PoP\Root\App::getHookManager()->addFilter('gd_ppp_previewurl_target', 'popthemewassupPppPreviewurlTarget');
function popthemewassupPppPreviewurlTarget($target)
{
    return POP_TARGET_QUICKVIEW;
}
