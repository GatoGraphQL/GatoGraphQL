<?php
use PoP\ComponentModel\State\ApplicationState;
use PoP\Engine\Route\RouteUtils;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPSchema\CustomPostMedia\Misc\MediaHelpers;
use PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;
use PoPSchema\CustomPosts\Routing\RouteNatures as CustomPostRouteNatures;
use PoPSchema\Media\Facades\MediaTypeAPIFacade;
use PoPSchema\Users\Facades\UserTypeAPIFacade;
use PoPSchema\Users\Routing\RouteNatures as UserRouteNatures;

class PoP_Module_Processor_PageTabsLayouts extends PoP_Module_Processor_PageTabsLayoutsBase
{
    public const MODULE_LAYOUT_PAGETABS_HOME = 'layout-pagetabs-home';
    public const MODULE_LAYOUT_PAGETABS_TAG = 'layout-pagetabs-tag';
    public const MODULE_LAYOUT_PAGETABS_PAGE = 'layout-pagetabs-page';
    public const MODULE_LAYOUT_PAGETABS_ROUTE = 'layout-pagetabs-route';
    public const MODULE_LAYOUT_PAGETABS_SINGLE = 'layout-pagetabs-single';
    public const MODULE_LAYOUT_PAGETABS_AUTHOR = 'layout-pagetabs-author';
    public const MODULE_LAYOUT_PAGETABS_404 = 'layout-pagetabs-404';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_PAGETABS_HOME],
            [self::class, self::MODULE_LAYOUT_PAGETABS_TAG],
            [self::class, self::MODULE_LAYOUT_PAGETABS_PAGE],
            [self::class, self::MODULE_LAYOUT_PAGETABS_ROUTE],
            [self::class, self::MODULE_LAYOUT_PAGETABS_SINGLE],
            [self::class, self::MODULE_LAYOUT_PAGETABS_AUTHOR],
            [self::class, self::MODULE_LAYOUT_PAGETABS_404],
        );
    }

    protected function getFontawesome(array $module, array &$props)
    {
        $fontawesomes = array(
            self::MODULE_LAYOUT_PAGETABS_HOME => 'fa-home',
            self::MODULE_LAYOUT_PAGETABS_TAG => 'fa-hashtag',
            self::MODULE_LAYOUT_PAGETABS_404 => 'fa-exclamation-circle',
        );
        if ($fontawesome = $fontawesomes[$module[1]] ?? null) {
            return $fontawesome;
        }
        return parent::getFontawesome($module, $props);
    }
    protected function getThumb(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_PAGETABS_AUTHOR:
                $author = \PoP\Root\App::getState(['routing', 'queried-object-id']);
                $avatar = gdGetAvatar($author, GD_AVATAR_SIZE_16);
                return array(
                    'src' => $avatar['src'],
                    'w' => $avatar['size'],
                    'h' => $avatar['size']
                );

            case self::MODULE_LAYOUT_PAGETABS_PAGE:
            case self::MODULE_LAYOUT_PAGETABS_SINGLE:
                $post_id = \PoP\Root\App::getState(['routing', 'queried-object-id']);
                if ($post_thumb_id = MediaHelpers::getThumbId($post_id)) {
                    $mediaTypeAPI = MediaTypeAPIFacade::getInstance();
                    $thumb = $mediaTypeAPI->getImageProperties($post_thumb_id, 'favicon');
                    return array(
                        'src' => $thumb['src'],
                        'w' => $thumb['width'],
                        'h' => $thumb['height']
                    );
                }
                break;
        }

        return parent::getThumb($module, $props);
    }
    // protected function getPretitle(array $module, array &$props)
    // {
    //     $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
    //     switch ($module[1]) {
    //         case self::MODULE_LAYOUT_PAGETABS_AUTHOR:
    //         case self::MODULE_LAYOUT_PAGETABS_SINGLE:
    //             $natures = array(
    //                 self::MODULE_LAYOUT_PAGETABS_AUTHOR => UserRouteNatures::USER,
    //                 self::MODULE_LAYOUT_PAGETABS_SINGLE => CustomPostRouteNatures::CUSTOMPOST,
    //             );

    //             // For the default page add the thumbnail. For the others, add the pretitle
    //             $page_id = RequestUtils::getRoute();
    //             if ($page_id != RequestUtils::getNatureDefaultPage($natures[$module[1]] ?? null)) {
    //                 return $cmsengineapi->getTitle($page_id);
    //             }
    //             break;
    //     }

    //     return parent::getPretitle($module, $props);
    // }
    protected function getTitle(array $module, array &$props)
    {
        $userTypeAPI = UserTypeAPIFacade::getInstance();
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        $applicationtaxonomyapi = \PoP\ApplicationTaxonomies\FunctionAPIFactory::getInstance();
        switch ($module[1]) {
            case self::MODULE_LAYOUT_PAGETABS_AUTHOR:
                $author = \PoP\Root\App::getState(['routing', 'queried-object-id']);
                return $userTypeAPI->getUserDisplayName($author);

            case self::MODULE_LAYOUT_PAGETABS_ROUTE:
                $route = \PoP\Root\App::getState('route');
                return RouteUtils::getRouteTitle($route);

            case self::MODULE_LAYOUT_PAGETABS_PAGE:
            case self::MODULE_LAYOUT_PAGETABS_SINGLE:
                $customPostID = \PoP\Root\App::getState(['routing', 'queried-object-id']);
                return $customPostTypeAPI->getTitle($customPostID);

            case self::MODULE_LAYOUT_PAGETABS_TAG:
                $tag_id = \PoP\Root\App::getState(['routing', 'queried-object-id']);
                return $applicationtaxonomyapi->getTagSymbolName($tag_id);
        }

        $titles = array(
            self::MODULE_LAYOUT_PAGETABS_HOME => TranslationAPIFacade::getInstance()->__('Home', 'poptheme-wassup'),
            self::MODULE_LAYOUT_PAGETABS_404 => TranslationAPIFacade::getInstance()->__('Page not found!', 'poptheme-wassup'),
        );
        if ($title = $titles[$module[1]] ?? null) {
            return $title;
        }
        return parent::getTitle($module, $props);
    }
}


