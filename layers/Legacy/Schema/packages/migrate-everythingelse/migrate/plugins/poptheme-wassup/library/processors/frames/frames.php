<?php
define('POP_HOOK_PAGESECTIONS_SIDE_LOGOSIZE', 'pagesections-side-logosize');

use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\Modules\ModuleUtils;
use PoPCMSSchema\SchemaCommons\Facades\CMS\CMSServiceFacade;
use PoP\Engine\Route\RouteUtils;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoPTheme_Wassup_Module_Processor_Frames extends PoPEngine_QueryDataModuleProcessorBase
{
    public const MODULE_FRAME_TOP = 'frame-top';
    public const MODULE_FRAME_SIDE = 'frame-side';
    public const MODULE_FRAME_BACKGROUND = 'frame-background';
    public const MODULE_FRAME_TOPSIMPLE = 'frame-topsimple';
    public const MODULE_FRAME_TOPEMBED = 'frame-topembed';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FRAME_TOP],
            [self::class, self::MODULE_FRAME_SIDE],
            [self::class, self::MODULE_FRAME_BACKGROUND],
            [self::class, self::MODULE_FRAME_TOPSIMPLE],
            [self::class, self::MODULE_FRAME_TOPEMBED],
        );
    }

    public function getTemplateResource(array $module, array &$props): ?array
    {
        switch ($module[1]) {
            case self::MODULE_FRAME_TOP:
                return [PoPTheme_Wassup_TemplateResourceLoaderProcessor::class, PoPTheme_Wassup_TemplateResourceLoaderProcessor::RESOURCE_FRAME_TOP];

            case self::MODULE_FRAME_SIDE:
                return [PoPTheme_Wassup_TemplateResourceLoaderProcessor::class, PoPTheme_Wassup_TemplateResourceLoaderProcessor::RESOURCE_FRAME_SIDE];

            case self::MODULE_FRAME_BACKGROUND:
                return [PoPTheme_Wassup_TemplateResourceLoaderProcessor::class, PoPTheme_Wassup_TemplateResourceLoaderProcessor::RESOURCE_FRAME_BACKGROUND];

            case self::MODULE_FRAME_TOPSIMPLE:
            case self::MODULE_FRAME_TOPEMBED:
                return [PoPTheme_Wassup_TemplateResourceLoaderProcessor::class, PoPTheme_Wassup_TemplateResourceLoaderProcessor::RESOURCE_FRAME_TOPSIMPLE];
        }

        return null;
    }

    public function getJsmethods(array $module, array &$props)
    {
        $ret = parent::getJsmethods($module, $props);

        switch ($module[1]) {
            case self::MODULE_FRAME_TOP:
            case self::MODULE_FRAME_SIDE:
            case self::MODULE_FRAME_TOPSIMPLE:
            case self::MODULE_FRAME_TOPEMBED:
                // $this->addJsmethod($ret, 'offcanvasToggle', 'togglenav');
                $this->addJsmethod($ret, 'offcanvasToggle', 'togglenavigator');
                $this->addJsmethod($ret, 'offcanvasToggle', 'togglepagetabs');
                break;
        }
        switch ($module[1]) {
            case self::MODULE_FRAME_TOP:
            case self::MODULE_FRAME_SIDE:
            case self::MODULE_FRAME_TOPSIMPLE:
                // Comment Leo 24/10/2016: This is the only difference between self::MODULE_FRAME_TOPSIMPLE and self::MODULE_FRAME_TOPEMBED:
                // the Embed does not use the Side, as such do not execute this JS below which will add class "active-side" and so create a bug
                $this->addJsmethod($ret, 'offcanvasToggle', 'togglenav');
                break;
        }
        switch ($module[1]) {
            case self::MODULE_FRAME_TOP:
            case self::MODULE_FRAME_TOPSIMPLE:
            case self::MODULE_FRAME_TOPEMBED:
                $this->addJsmethod($ret, 'doNothing', 'void-link');
                $this->addJsmethod($ret, 'tooltip', 'logo');
                $this->addJsmethod($ret, 'tooltip', 'togglenav');
                $this->addJsmethod($ret, 'tooltip', 'togglenavigator');
                $this->addJsmethod($ret, 'tooltip', 'togglepagetabs');
                $this->addJsmethod($ret, 'offcanvasToggle', 'togglenav-xs');
                $this->addJsmethod($ret, 'offcanvasToggle', 'togglepagetabs-xs');

                // Save the state of the Main Navigation being open or not: open by default, by adding class "active-side"
                // to the pageSectionGroup, but if the user clicks, then it's dismissed
                if (\PoP\Root\App::applyFilters(
                    'PoP_Module_Processor_CustomPageSections:jsmethods:toggleside',
                    true,
                    $module
                )
                ) {
                    $this->addJsmethod($ret, 'cookieToggleClass', 'togglenav');
                }
                break;
        }
        switch ($module[1]) {
            case self::MODULE_FRAME_TOP:
                $this->addJsmethod($ret, 'switchTargetClass', 'togglesearch-xs');
                $this->addJsmethod($ret, 'scrollbarVertical', 'notifications');
                $this->addJsmethod($ret, 'clearDatasetCount', 'notification-link');
                $this->addJsmethod($ret, 'clearDatasetCountOnUserLoggedOut', 'notification-link');
                break;

            case self::MODULE_FRAME_TOPSIMPLE:
            case self::MODULE_FRAME_TOPEMBED:
                $this->addJsmethod($ret, 'fullscreen', 'fullscreen');
                $this->addJsmethod($ret, 'fullscreen', 'fullscreen-xs');
                $this->addJsmethod($ret, 'newWindow', 'new-window');
                $this->addJsmethod($ret, 'newWindow', 'new-window-xs');
                $this->addJsmethod($ret, 'tooltip', 'fullscreen');
                $this->addJsmethod($ret, 'tooltip', 'new-window');
                break;
        }
        switch ($module[1]) {
            case self::MODULE_FRAME_SIDE:
            case self::MODULE_FRAME_BACKGROUND:
                $this->addJsmethod($ret, 'scrollbarVertical');
                break;
        }

        return $ret;
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_FRAME_TOP:
                $this->setProp([AAL_PoPProcessors_Module_Processor_NotificationBlocks::class, AAL_PoPProcessors_Module_Processor_NotificationBlocks::MODULE_BLOCK_NOTIFICATIONS_SCROLL_LIST], $props, 'set-datasetcount', true);
                $this->setProp([AAL_PoPProcessors_Module_Processor_NotificationDataloads::class, AAL_PoPProcessors_Module_Processor_NotificationDataloads::MODULE_DATALOAD_NOTIFICATIONS_SCROLL_LIST], $props, 'lazy-load', true);
                break;
        }

        parent::initModelProps($module, $props);
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_FRAME_TOP:
                $ret = array_merge(
                    $ret,
                    array(
                        [PoP_Module_Processor_CustomMenuMultiples::class, PoP_Module_Processor_CustomMenuMultiples::MODULE_MULTIPLE_MENU_TOP_ADDNEW],
                        [PoP_Module_Processor_CustomMenuMultiples::class, PoP_Module_Processor_CustomMenuMultiples::MODULE_MULTIPLE_MENU_TOPNAV_USERLOGGEDIN],
                        [PoP_Module_Processor_CustomMenuMultiples::class, PoP_Module_Processor_CustomMenuMultiples::MODULE_MULTIPLE_MENU_TOPNAV_USERNOTLOGGEDIN],
                        [PoP_Module_Processor_CustomMenuMultiples::class, PoP_Module_Processor_CustomMenuMultiples::MODULE_MULTIPLE_MENU_TOPNAV_ABOUT],
                        [PoP_Core_Module_Processor_Forms::class, PoP_Core_Module_Processor_Forms::MODULE_FORM_EVERYTHINGQUICKLINKS],
                    )
                );
                if (defined('POP_CLUSTERCOMMONPAGES_INITIALIZED')) {
                    $ret[] = [GD_ClusterCommonPages_Module_Processor_CustomSectionBlocks::class, GD_ClusterCommonPages_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_OURSPONSORS_TOPNAV_SCROLL];
                }
                if (defined('POP_NOTIFICATIONSPROCESSORS_INITIALIZED')) {
                    $ret[] = [AAL_PoPProcessors_Module_Processor_NotificationBlocks::class, AAL_PoPProcessors_Module_Processor_NotificationBlocks::MODULE_BLOCK_NOTIFICATIONS_SCROLL_LIST];
                }
                break;

            case self::MODULE_FRAME_SIDE:
                $ret[] = [PoP_Module_Processor_SideGroups::class, PoP_Module_Processor_SideGroups::MODULE_GROUP_SIDE];
                break;

            case self::MODULE_FRAME_BACKGROUND:
                if (PoP_ApplicationProcessors_Utils::addBackgroundMenu()) {
                    $ret[] = [self::class, self::MODULE_GROUP_BACKGROUNDMENU];
                }
                break;
        }

        return $ret;
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $cmsService = CMSServiceFacade::getInstance();
        $cmsuseraccountapi = \PoP\UserAccount\FunctionAPIFactory::getInstance();
        $cmsapplicationapi = \PoP\Application\FunctionAPIFactory::getInstance();

        $ret = parent::getImmutableConfiguration($module, $props);

        $togglenav = TranslationAPIFacade::getInstance()->__('Toggle Navigation', 'poptheme-wassup');
        $togglehistory = TranslationAPIFacade::getInstance()->__('Toggle Browsing Tabs', 'poptheme-wassup');

        switch ($module[1]) {
         // Comment Leo 24/10/2016: This is the only difference between self::MODULE_FRAME_TOPSIMPLE and self::MODULE_FRAME_TOPEMBED:
         // the Embed does not use the Side, as such do not execute this JS below which will add class "active-side" and so create a bug
            case self::MODULE_FRAME_TOP:
            case self::MODULE_FRAME_TOPSIMPLE:
                $ret['offcanvas-sidenav-target'] = '#'.POP_MODULEID_PAGESECTIONCONTAINERID_SIDE;
                $ret['offcanvas-navigator-target'] = '#'.POP_MODULEID_PAGESECTIONCONTAINERID_NAVIGATOR;

                // Save the state of the main navigation (open or close) with a cookie
                $ret['togglenav-params'] = array(
                    'data-cookieid' => 'togglenav',
                    'data-cookietarget' => '#'.POP_MODULEID_PAGESECTIONGROUP_ID,
                    'data-cookieclass' => 'active-side',
                    'data-togglecookiebtn' => 'self',

                    // Comment Leo 17/10/2017: Changed 'initial' from 'notset' to 'toggle', so that it removes class 'active-side'
                    // if the user had closed the side menu (hence there will be a cookie 'dismissed')
                    // Needed after introducing class 'active-side' directly in pagesection-group through serverside-rendering,
                    // so that we can still keep the state of the open/close side navigation
                    'data-initial' => 'toggle',
                );
                break;
        }

        switch ($module[1]) {
            case self::MODULE_FRAME_TOP:
                $title = $cmsapplicationapi->getSiteName();

                // Generate the small and large logos
                $logo_sizes = array(
                    'small',
                    'large-white'
                );
                foreach ($logo_sizes as $size) {
                    $logo = gdLogo($size);
                    $ret['logo-'.$size] = array(
                        'src' => $logo[0],
                        'width' => $logo[1],
                        'height' => $logo[2],
                        'title' => $title
                    );
                }

                $ret['offcanvas-pagetabs-target'] = '#'.POP_MODULEID_PAGESECTIONCONTAINERID_BODYTABS;
                // $ret['offcanvas-sidenav-target'] = '#'.POP_MODULEID_PAGESECTIONCONTAINERID_SIDE;
                // $ret['offcanvas-navigator-target'] = '#'.POP_MODULEID_PAGESECTIONCONTAINERID_NAVIGATOR;
                $ret['togglesearch-target'] = '#'.POP_MODULEID_PAGESECTIONCONTAINERID_TOP;

                // // Save the state of the main navigation (open or close) with a cookie
                // $ret['togglenav-params'] = array(
                //     'data-cookieid' => 'togglenav',
                //     'data-cookietarget' => '#'.POP_MODULEID_PAGESECTIONGROUP_ID,
                //     'data-cookieclass' => 'active-side',
                //     'data-togglecookiebtn' => 'self',
                // );

                // If class AAL_PoPProcessors is not initialized, then the class below does not exist, and will raise an exception
                if (defined('POP_NOTIFICATIONSPROCESSORS_INITIALIZED')) {
                    $ret['ids'] = array(
                        'notifications-count' => AAL_PoPProcessors_NotificationUtils::getNotificationcountId(),//[self::class, self::MODULE_ID_NOTIFICATIONSCOUNT],
                    );
                    $ret['params'] = array(
                        'notifications-link' => array(
                            'data-datasetcount-target' => '#'.AAL_PoPProcessors_NotificationUtils::getNotificationcountId(),
                            'data-datasetcount-updatetitle' => true,
                        )
                    );
                }

                // Allow TPPDebate to override the social media
                $ret['socialmedias'] = \PoP\Root\App::applyFilters(
                    'PoP_Module_Processor_CustomPageSections:frame-top:socialmedias',
                    array()
                );
                $ret['icons'] = array(
                    'notifications' => 'glyphicon-bell',
                    'sponsors' => 'glyphicon-heart',
                    'about' => 'glyphicon-info-sign',
                    'settings' => 'glyphicon-cog',
                    'togglenavigation' => 'glyphicon-menu-hamburger',
                    'togglesearch' => 'glyphicon-option-horizontal',// 'glyphicon-search',
                    'togglepagetabs' => 'glyphicon-time',
                    'account' => 'glyphicon-user',
                    'addcontent' => 'glyphicon-plus',
                );
                $ret['links'] = array(
                    'home' => GeneralUtils::maybeAddTrailingSlash($cmsService->getHomeURL()),
                    'settings' => RouteUtils::getRouteURL(POP_USERPLATFORM_ROUTE_SETTINGS),
                    'login' => $cmsuseraccountapi->getLoginURL(),
                    'notifications' => RouteUtils::getRouteURL(POP_NOTIFICATIONS_ROUTE_NOTIFICATIONS),
                );

                if (defined('POP_USERAVATAR_INITIALIZED')) {
                    $ret['links']['useravatar'] = RouteUtils::getRouteURL(POP_USERAVATAR_ROUTE_EDITAVATAR);
                }
                // Allow TPPDebate to override the titles
                $ret[GD_JS_TITLES] = \PoP\Root\App::applyFilters(
                    'PoP_Module_Processor_CustomPageSections:frame-top:titles',
                    array(
                        'home' => TranslationAPIFacade::getInstance()->__('Home', 'poptheme-wassup'),
                        'account-loading-msg' => '<i class="fa fa-circle-o-notch fa-spin fa-inverse fa-2x"></i>', //GD_CONSTANT_LOADING_SPINNERINVERSE,
                        'togglesearch' => TranslationAPIFacade::getInstance()->__('Toggle Search', 'poptheme-wassup'),
                        'togglepagetabs' => $togglehistory,
                        'togglenavigation' => $togglenav,
                        // 'featuredcommunities' => TranslationAPIFacade::getInstance()->__('Featured Organizations', 'poptheme-wassup'),
                        // Override the footer value
                        'footer' => sprintf(
                            TranslationAPIFacade::getInstance()->__('Powered by <a href="%s" target="_blank">the PoP framework</a> through <a href="%s" target="_blank">Verticals</a>', 'poptheme-wassup'),
                            // Allow qTrans to add the language
                            \PoP\Root\App::applyFilters('PoP_Module_Processor_CustomPageSections:footer:poweredby-links', POPTHEME_WASSUP_LINK_GETPOP),
                            \PoP\Root\App::applyFilters('PoP_Module_Processor_CustomPageSections:footer:poweredby-links', POPTHEME_WASSUP_LINK_VERTICALS)
                        ),
                        'about' => TranslationAPIFacade::getInstance()->__('About us', 'poptheme-wassup'),
                        'myprofile' => TranslationAPIFacade::getInstance()->__('My Profile', 'poptheme-wassup'),
                        'loginaddprofile' => TranslationAPIFacade::getInstance()->__('Login/Register', 'poptheme-wassup'),
                        'settings' => TranslationAPIFacade::getInstance()->__('Settings', 'poptheme-wassup'),
                        'notifications' => TranslationAPIFacade::getInstance()->__('Notifications', 'poptheme-wassup'),
                        'sponsors-description' => TranslationAPIFacade::getInstance()->__('<em>Many thanks to our <strong>Sponsors and Supporters</strong>:</em>', 'poptheme-wassup'),
                        'sponsors' => TranslationAPIFacade::getInstance()->__('Our Sponsors and Supporters', 'poptheme-wassup'),
                        'viewallsponsors' => sprintf(
                            TranslationAPIFacade::getInstance()->__('View all <a href="%s">%s</a>', 'poptheme-wassup'),
                            RouteUtils::getRouteURL(POP_CLUSTERCOMMONPAGES_ROUTE_ABOUT_OURSPONSORS),
                            RouteUtils::getRouteTitle(POP_CLUSTERCOMMONPAGES_ROUTE_ABOUT_OURSPONSORS)
                        ),
                        'viewallnotifications' => sprintf(
                            TranslationAPIFacade::getInstance()->__('View all <a href="%s">%s</a>', 'poptheme-wassup'),
                            RouteUtils::getRouteURL(POP_NOTIFICATIONS_ROUTE_NOTIFICATIONS),
                            RouteUtils::getRouteTitle(POP_NOTIFICATIONS_ROUTE_NOTIFICATIONS)
                        ),
                        'sponsorus' => sprintf(
                            '<a href="%s">%s</a>',
                            '#', //$pageTypeAPI->getPermalink(POP_CLUSTERCOMMONPAGES_PAGE_ABOUT_SPONSORUS),
                            'Sponsor us'//$pageTypeAPI->getTitle(POP_CLUSTERCOMMONPAGES_PAGE_ABOUT_SPONSORUS)
                        ),
                        'addcontent' => TranslationAPIFacade::getInstance()->__('Add content', 'poptheme-wassup'),
                        'addcontent-right' => TranslationAPIFacade::getInstance()->__('Add new...', 'poptheme-wassup'),
                        'addcontent-left' => '<span class="glyphicon glyphicon-plus"></span>',
                        'account-right' => sprintf(
                            TranslationAPIFacade::getInstance()->__('Your account in %s', 'poptheme-wassup'),
                            $title
                        ),
                        'account-left' => '<span class="glyphicon glyphicon-user"></span>',
                    )
                );
                if (defined('POP_USERAVATAR_INITIALIZED')) {
                    $ret[GD_JS_TITLES]['useravatar'] = RouteUtils::getRouteTitle(POP_USERAVATAR_ROUTE_EDITAVATAR);
                    $ret[GD_JS_CLASSES]['useravatar'] = 'btn btn-xs btn-block btn-link';
                }
                $ret[GD_JS_CLASSES]['socialmedia'] = 'btn btn-link btn-text-left';
                $ret[GD_JS_CLASSES]['notifications'] = 'notifications pop-waypoints-context scrollable perfect-scrollbar vertical';
                $ret[GD_JS_CLASSES]['notifications-count'] = 'badge';

                $ret[GD_JS_SUBMODULEOUTPUTNAMES]['menu-addnew'] = ModuleUtils::getModuleOutputName([PoP_Module_Processor_CustomMenuMultiples::class, PoP_Module_Processor_CustomMenuMultiples::MODULE_MULTIPLE_MENU_TOP_ADDNEW]);
                $ret[GD_JS_SUBMODULEOUTPUTNAMES]['menu-userloggedin'] = ModuleUtils::getModuleOutputName([PoP_Module_Processor_CustomMenuMultiples::class, PoP_Module_Processor_CustomMenuMultiples::MODULE_MULTIPLE_MENU_TOPNAV_USERLOGGEDIN]);
                $ret[GD_JS_SUBMODULEOUTPUTNAMES]['menu-usernotloggedin'] = ModuleUtils::getModuleOutputName([PoP_Module_Processor_CustomMenuMultiples::class, PoP_Module_Processor_CustomMenuMultiples::MODULE_MULTIPLE_MENU_TOPNAV_USERNOTLOGGEDIN]);
                $ret[GD_JS_SUBMODULEOUTPUTNAMES]['menu-about'] = ModuleUtils::getModuleOutputName([PoP_Module_Processor_CustomMenuMultiples::class, PoP_Module_Processor_CustomMenuMultiples::MODULE_MULTIPLE_MENU_TOPNAV_ABOUT]);
                $ret[GD_JS_SUBMODULEOUTPUTNAMES]['search'] = ModuleUtils::getModuleOutputName([PoP_Core_Module_Processor_Forms::class, PoP_Core_Module_Processor_Forms::MODULE_FORM_EVERYTHINGQUICKLINKS]);
                if (defined('POP_CLUSTERCOMMONPAGES_INITIALIZED')) {
                    $ret[GD_JS_SUBMODULEOUTPUTNAMES]['block-oursponsors'] = ModuleUtils::getModuleOutputName([GD_ClusterCommonPages_Module_Processor_CustomSectionBlocks::class, GD_ClusterCommonPages_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_OURSPONSORS_TOPNAV_SCROLL]);
                }
                if (defined('POP_NOTIFICATIONSPROCESSORS_INITIALIZED')) {
                    $ret[GD_JS_SUBMODULEOUTPUTNAMES]['block-notifications'] = ModuleUtils::getModuleOutputName([AAL_PoPProcessors_Module_Processor_NotificationBlocks::class, AAL_PoPProcessors_Module_Processor_NotificationBlocks::MODULE_BLOCK_NOTIFICATIONS_SCROLL_LIST]);
                }
                break;

            case self::MODULE_FRAME_TOPSIMPLE:
            case self::MODULE_FRAME_TOPEMBED:
                // Generate the small logo
                $logo_sizes = array(
                    'small',
                );
                foreach ($logo_sizes as $size) {
                    $logo = gdLogo($size);
                    $ret['logo-'.$size] = array(
                        'src' => $logo[0],
                        'width' => $logo[1],
                        'height' => $logo[2],
                    );
                }

                $ret['offcanvas-pagetabs-target'] = '#'.POP_MODULEID_PAGESECTIONCONTAINERID_BODYTABS;

                $ret['targets'] = array(
                    'home' => '_blank',
                );
                $ret['links'] = array(
                    'home' => GeneralUtils::maybeAddTrailingSlash($cmsService->getHomeURL()),
                );
                $ret['icons'] = array(
                    'togglenavigation' => 'glyphicon-menu-hamburger',
                );
                $ret[GD_JS_TITLES] = array(
                    'togglenavigation' => $togglenav,
                    'togglepagetabs' => $togglehistory,
                    'home' => TranslationAPIFacade::getInstance()->__('Home', 'poptheme-wassup'),
                    'homenewtab' => sprintf(
                        TranslationAPIFacade::getInstance()->__('Open %s in a new tab', 'poptheme-wassup'),
                        $cmsapplicationapi->getSiteName()
                    ),
                    'fullscreen' => TranslationAPIFacade::getInstance()->__('Toggle full screen', 'poptheme-wassup'),
                    'newwindow' => TranslationAPIFacade::getInstance()->__('Open in new window', 'poptheme-wassup'),
                );
                break;

            case self::MODULE_FRAME_SIDE:
                $title = $cmsapplicationapi->getSiteName();

                // Allow the ThemeStyle to override the logo size
                $size = \PoP\Root\App::applyFilters(POP_HOOK_PAGESECTIONS_SIDE_LOGOSIZE, 'large');
                $logo = gdLogo($size);
                $ret['logo-main'] = array(
                    'src' => $logo[0],
                    'width' => $logo[1],
                    'height' => $logo[2],
                    'title' => $title
                );

                $ret['links'] = array(
                    // GeneralUtils::maybeAddTrailingSlash because of qTrans: it will output link https://www.mesym.com/zh and it fails with popURLInterceptors, which expects https://www.mesym.com/zh/
                    'home' => GeneralUtils::maybeAddTrailingSlash($cmsService->getHomeURL()),
                );
                $ret[GD_JS_TITLES] = array(
                    'home' => $cmsapplicationapi->getSiteName(),
                    'togglenavigation' => $togglenav,
                );

                $side = [PoP_Module_Processor_SideGroups::class, PoP_Module_Processor_SideGroups::MODULE_GROUP_SIDE];
                $ret[GD_JS_SUBMODULEOUTPUTNAMES]['side'] = ModuleUtils::getModuleOutputName($side);
                break;

            case self::MODULE_FRAME_BACKGROUND:
                $ret[GD_JS_TITLES]['title'] = PoP_ApplicationProcessors_Utils::getWelcomeTitle(true);
                $ret[GD_JS_TITLES]['description'] = gdGetWebsiteDescription();
                if ($img = gdImagesBackground()) {
                    $ret['img'] = array(
                        'src' => $img
                    );
                }

                if (PoP_ApplicationProcessors_Utils::addBackgroundMenu()) {
                    $ret[GD_JS_SUBMODULEOUTPUTNAMES]['menu'] = ModuleUtils::getModuleOutputName([self::class, self::MODULE_GROUP_BACKGROUNDMENU]);
                }
                break;
        }

        return $ret;
    }

    public function getMutableonrequestConfiguration(array $module, array &$props): array
    {
        $ret = parent::getMutableonrequestConfiguration($module, $props);

        $cmsapplicationapi = \PoP\Application\FunctionAPIFactory::getInstance();
        switch ($module[1]) {
            case self::MODULE_FRAME_TOPSIMPLE:
            case self::MODULE_FRAME_TOPEMBED:
                $ret[GD_JS_TITLES]['document'] = \PoP\Root\App::applyFilters(
                    'GD_DataLoad_QueryInputOutputHandler_FrameTopSimplePageSection:document_title',
                    $cmsapplicationapi->getDocumentTitle()
                );
                break;
        }

        return $ret;
    }
}


