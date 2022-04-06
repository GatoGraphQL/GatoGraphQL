<?php
use PoP\ComponentModel\Facades\HelperServices\RequestHelperServiceFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\Users\Facades\UserTypeAPIFacade;
?>

<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11" prefix="og: http://ogp.me/ns#">
    <meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
    <?php /* DNS Prefetching */ ?>
    <?php foreach (PoP_WebPlatform_ConfigurationUtils::getAllowedDomains() as $domain) : ?>
        <link rel="dns-prefetch" href="<?php echo $domain ?>">
    <?php endforeach; ?>
    <?php /* For Bootstrap v3.0: http://getbootstrap.com/getting-started/ */ ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <?php if (is_ssl()) : ?>
        <?php /* Avoid insecure HTTP requests over HTTPS. Taken from https://developers.google.com/web/fundamentals/security/prevent-mixed-content/fixing-mixed-content */ ?>
        <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <?php endif; ?>
    <?php $cmsapplicationapi = \PoP\Application\FunctionAPIFactory::getInstance(); ?>
    <?php $htmlcssplatformapi = \PoP\EngineHTMLCSSPlatform\FunctionAPIFactory::getInstance(); ?>
    <?php $userTypeAPI = UserTypeAPIFacade::getInstance(); ?>
    <?php $applicationtaxonomyapi = \PoP\ApplicationTaxonomies\FunctionAPIFactory::getInstance(); ?>
    <?php $cmsapplicationhelpers = \PoP\Application\HelperAPIFactory::getInstance(); ?>
    <?php $cmsapplicationapi = \PoP\Application\FunctionAPIFactory::getInstance(); ?>
    <?php $title = $cmsapplicationapi->getDocumentTitle(); ?>
    <?php $encoded_title = $cmsapplicationhelpers->escapeHTML($title); ?>
    <?php $site_name = $cmsapplicationapi->getSiteName(); ?>
    <?php $js_disabled = PoP_WebPlatform_ServerUtils::disableJs(); ?>
    <title><?php echo $title ?></title>
    <?php if (/*\PoP\Root\App::getState(['routing', 'is-search']) || */\PoP\Root\App::getState(['routing', 'is-user'])) : ?>
    <meta name="robots" content="noindex, nofollow" />
    <?php endif ?>
    <link rel="alternate" type="application/rss+xml" href="<?php bloginfo('rss2_url') ?>" title="<?php printf(TranslationAPIFacade::getInstance()->__('%s latest posts', 'poptheme-wassup'), $cmsapplicationhelpers->escapeHTML($site_name, 1)) ?>" />
    <link rel="alternate" type="application/rss+xml" href="<?php bloginfo('comments_rss2_url') ?>" title="<?php printf(TranslationAPIFacade::getInstance()->__('%s latest comments', 'poptheme-wassup'), $cmsapplicationhelpers->escapeHTML($site_name, 1)) ?>" />
    <?php /* This outputs site_url( 'xmlrpc.php' ), and because the xmlrpc.php is blocked, no need to add it     */ ?>
    <?php /* <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />     */ ?>
    <link rel="shortcut icon" href="<?php echo gdGetFavicon() ?>" />
    <?php /* meta for Facebook / Twitter / Google Search */ ?>
    <meta name="twitter:card" content="summary">
    <meta property="og:site_name" content="<?php echo $site_name ?>">
    <meta property="og:title" content="<?php echo $encoded_title ?>">
    <meta name="twitter:title" content="<?php echo $encoded_title ?>">
    <?php $requestHelperService = RequestHelperServiceFacade::getInstance(); ?>
    <?php $url = $requestHelperService->getCurrentURL(); ?>
    <?php if ($url != urldecode($requestHelperService->getRequestedFullURL())) : ?>
        <link rel="canonical" href="<?php echo $url ?>">
    <?php endif; ?>
    <meta property="og:url" content="<?php echo $url ?>">
    <meta name="twitter:url" content="<?php echo $url ?>">
    <?php $twitter_user = gdTwitterUser() ?>
    <meta name="twitter:site" content="<?php echo $twitter_user ?>">
    <meta name="twitter:creator" content="<?php echo $twitter_user ?>">
    <?php
    if (\PoP\Root\App::getState(['routing', 'is-custompost']) || \PoP\Root\App::getState(['routing', 'is-page'])) {
        $description = gdGetPostDescription();
    } elseif (\PoP\Root\App::getState(['routing', 'is-generic'])) {
        $description = gdHeaderRouteDescription();
    } elseif (\PoP\Root\App::getState(['routing', 'is-user'])) {
        $author = \PoP\Root\App::getState(['routing', 'queried-object-id']);
        $curauth = $userTypeAPI->getUserByID($author);
        $description = sprintf(TranslationAPIFacade::getInstance()->__('View %1$s profile and get in touch through %2$s.', 'poptheme-wassup'), $curauth->display_name, $site_name);
    } elseif (\PoP\Root\App::getState(['routing', 'is-tag'])) {
        $tag_id = \PoP\Root\App::getState(['routing', 'queried-object-id']);
        $description = sprintf(
            TranslationAPIFacade::getInstance()->__('Entries tagged “%1$s” in %2$s.', 'poptheme-wassup'),
            $applicationtaxonomyapi->getTagSymbolName($tag_id),
            $site_name
        );
    }
    // If none of the above, always use the Website description
    if (!$description) {
        $description = gdHeaderSiteDescription();
    }
    ?>
    <meta name="description" content="<?php echo $description ?>">
    <meta property="og:description" content="<?php echo $description ?>">
    <meta name="twitter:description" content="<?php echo $description ?>">
    <?php
    $thumb = gdGetDocumentThumb();
    ?>
    <meta property="og:image" content="<?php echo $thumb['src'] ?>">
    <meta property="og:image:type" content="<?php echo $thumb['mime-type'] ?>">
    <meta property="og:image:width" content="<?php echo $thumb['width'] ?>">
    <meta property="og:image:height" content="<?php echo $thumb['height'] ?>">
    <meta name="twitter:image" content="<?php echo $thumb['src'] ?>">
    <meta name="twitter:image:width" content="<?php echo $thumb['width'] ?>">
    <meta name="twitter:image:height" content="<?php echo $thumb['height'] ?>">
    <?php /* Needed to pass the Lighthouse report for PWAs */ ?>
    <meta name="theme-color" content="<?php echo gdGetThemeColor() ?>"/>
    <?php $htmlcssplatformapi->printHeadHTML() ?>
    <?php /* For Bootstrap v3.0: http://getbootstrap.com/getting-started/ */ ?>
    <?php /* HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries */ ?>
    <!--[if lt IE 9]>
    <?php foreach (getCompatibilityJsFiles() as $file) : ?>
            <script src="<?php echo $file ?>"></script>
    <?php endforeach; ?>
    <![endif]-->
</head>
<body id="body" class="no-js <?php if (!defined('POP_SSR_INITIALIZED') || PoP_SSR_ServerUtils::disableServerSideRendering()) :
    ?>pop-loadingframe<?php
                             endif; ?> pop-loadinghtml <?php echo($js_disabled ? "" : "pop-loadingjs") ?> <?php echo gdClassesBody() ?>">
    <?php if (!$js_disabled) : ?>
        <script type="text/javascript">document.body.classList.remove("no-js");</script>
    <?php endif; ?>
    <?php // Comment Leo 08/06/2017: if loading the website using JS, instead of server-side, then make the wrapper initially hidden and show it through javascript?>
    <div id="wrapper" class="pop-fullscreen" <?php if (!defined('POP_SSR_INITIALIZED') || PoP_SSR_ServerUtils::disableServerSideRendering()) :
        ?> style="display: none;"<?php
                                             endif; ?>>
    <?php /* Show content only if JS enabled. */ ?>
    <?php if (!defined('POP_SSR_INITIALIZED') || PoP_SSR_ServerUtils::disableServerSideRendering()) :
        ?><script type="text/javascript">document.getElementById("wrapper").style.display = "block";</script><?php
    endif; ?>
        <div class="loading-screen">
    <?php require POPTHEME_WASSUP_TEMPLATES.'/loading-screen.php' ?>
        </div>
        <div id="background-screen" class="background-screen"></div>
    <?php

    // Container
    require POPTHEME_WASSUP_TEMPLATES.'/container.php';

    // Status
    require POPTHEME_WASSUP_TEMPLATES.'/status.php';

    // Include the Theme Header
    $theme_header = \PoP\Root\App::getState('theme-path').'/header.php';
    if (file_exists($theme_header)) {
        include $theme_header;
    }
    ?>
