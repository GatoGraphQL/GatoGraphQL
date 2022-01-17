<?php
use PoPCMSSchema\SchemaCommons\Facades\CMS\CMSServiceFacade;

/*
 * Function to remove all access to the backend (also edit one's profile) for Subscribers
 */
\PoP\Root\App::addAction('admin_init', 'blockDashboardAccess');
function blockDashboardAccess()
{

    // For the Contributor role, allow them to upload to Media Library
    // media-upload.php: accessed by JWP6 plugin
    global $pagenow;
    if (in_array($pagenow, array('async-upload.php', 'media-upload.php'))) {
        return;
    }
    
    // doingAjax: so as to allow them to login (with simplemodal-login)
    // or to subscribe to newsletter in Right Navigation widget
    if (userHasAdminAccess() || doingAjax()) {
        return;
    }

    // Otherwise, send them back home
    $cmsService = CMSServiceFacade::getInstance();
    $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
    $cmsengineapi->redirect($cmsService->getHomeURL());
    exit;
}
