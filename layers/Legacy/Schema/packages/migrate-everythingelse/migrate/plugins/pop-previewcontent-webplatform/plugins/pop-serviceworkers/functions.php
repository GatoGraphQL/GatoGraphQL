<?php

// Inject data-sw-networkfirst="true" to the preview link in the Add Post feedbackmessage
\PoP\Root\App::getHookManager()->addFilter('gd_ppp_previewurl_link_params', 'popSwReloadurlLinkattrs');

// Inject data-sw-networkfirst="true" to the preview link in the My Posts table
\PoP\Root\App::getHookManager()->addFilter('GD_UserPlatform_Module_Processor_Buttons:postpreview:params', 'popPppSwPreviewbtnNetworkfirst');
function popPppSwPreviewbtnNetworkfirst($params)
{
    $params['data-sw-networkfirst'] = 'true';
    return $params;
}
