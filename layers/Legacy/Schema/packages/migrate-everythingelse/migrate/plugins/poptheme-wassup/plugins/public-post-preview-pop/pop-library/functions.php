<?php
use PoP\Hooks\Facades\HooksAPIFacade;

// The target where to add the preview is the Quickview
HooksAPIFacade::getInstance()->addFilter('gd_ppp_previewurl_target', 'popthemewassupPppPreviewurlTarget');
function popthemewassupPppPreviewurlTarget($target)
{
    return POP_TARGET_QUICKVIEW;
}
