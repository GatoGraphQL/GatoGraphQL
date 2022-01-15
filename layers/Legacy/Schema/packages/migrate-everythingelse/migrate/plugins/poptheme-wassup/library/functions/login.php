<?php
use PoPSchema\SchemaCommons\Facades\CMS\CMSServiceFacade;

/**
 * Change the expiration of the login cookie to much longer than 2 weeks
 */
\PoP\Root\App::addFilter('popcms:authCookieExpiration', 'popthemeWassupAuthCookieExpiration');
function popthemeWassupAuthCookieExpiration($time)
{

    // 20 years (similar to GitHub)
    return 20 * 365 * DAY_IN_SECONDS;
}

/**
 * Change the Wordpress logo to MESYM logo in wp-login.php
 */
\PoP\Root\App::addAction('login_enqueue_scripts', 'gdLoginLogo');
function gdLoginLogo()
{
    $logo = gdLogo('large');
    $logo = \PoP\Root\App::applyFilters('gdLoginLogo', $logo); ?>
    <style type="text/css">
        body.login div#login h1 a {
            background-image: url(<?php echo $logo[0] ?>);
            width: <?php echo $logo[1] ?>px;
            height: <?php echo $logo[2] ?>px;
            background-size: <?php echo $logo[1] ?>px <?php echo $logo[2] ?>px;
            *margin-left: 10px;
        }
    </style>
    <?php
}
\PoP\Root\App::addFilter('login_headerurl', 'gdLoginLogoUrl');
function gdLoginLogoUrl()
{
    $cmsService = CMSServiceFacade::getInstance();
    return $cmsService->getHomeURL();
}
\PoP\Root\App::addFilter('login_headertitle', 'gdLoginLogoUrlTitle');
function gdLoginLogoUrlTitle()
{
    $cmsapplicationapi = \PoP\Application\FunctionAPIFactory::getInstance();
    return $cmsapplicationapi->getSiteName();
}
