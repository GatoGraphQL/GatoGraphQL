<?php
use PoP\ComponentModel\State\ApplicationState;
use PoP\Engine\Route\RouteUtils;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\CustomPostMedia\Misc\MediaHelpers;
use PoPCMSSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;
use PoPCMSSchema\CustomPosts\Routing\RequestNature as CustomPostRequestNature;
use PoPCMSSchema\Media\Facades\MediaTypeAPIFacade;
use PoPCMSSchema\Users\Facades\UserTypeAPIFacade;
use PoPCMSSchema\Users\Routing\RequestNature as UserRequestNature;

class PoP_Module_Processor_PageTabsLayouts extends PoP_Module_Processor_PageTabsLayoutsBase
{
    public final const COMPONENT_LAYOUT_PAGETABS_HOME = 'layout-pagetabs-home';
    public final const COMPONENT_LAYOUT_PAGETABS_TAG = 'layout-pagetabs-tag';
    public final const COMPONENT_LAYOUT_PAGETABS_PAGE = 'layout-pagetabs-page';
    public final const COMPONENT_LAYOUT_PAGETABS_ROUTE = 'layout-pagetabs-route';
    public final const COMPONENT_LAYOUT_PAGETABS_SINGLE = 'layout-pagetabs-single';
    public final const COMPONENT_LAYOUT_PAGETABS_AUTHOR = 'layout-pagetabs-author';
    public final const COMPONENT_LAYOUT_PAGETABS_404 = 'layout-pagetabs-404';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_PAGETABS_HOME],
            [self::class, self::COMPONENT_LAYOUT_PAGETABS_TAG],
            [self::class, self::COMPONENT_LAYOUT_PAGETABS_PAGE],
            [self::class, self::COMPONENT_LAYOUT_PAGETABS_ROUTE],
            [self::class, self::COMPONENT_LAYOUT_PAGETABS_SINGLE],
            [self::class, self::COMPONENT_LAYOUT_PAGETABS_AUTHOR],
            [self::class, self::COMPONENT_LAYOUT_PAGETABS_404],
        );
    }

    protected function getFontawesome(array $component, array &$props)
    {
        $fontawesomes = array(
            self::COMPONENT_LAYOUT_PAGETABS_HOME => 'fa-home',
            self::COMPONENT_LAYOUT_PAGETABS_TAG => 'fa-hashtag',
            self::COMPONENT_LAYOUT_PAGETABS_404 => 'fa-exclamation-circle',
        );
        if ($fontawesome = $fontawesomes[$component[1]] ?? null) {
            return $fontawesome;
        }
        return parent::getFontawesome($component, $props);
    }
    protected function getThumb(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_PAGETABS_AUTHOR:
                $author = \PoP\Root\App::getState(['routing', 'queried-object-id']);
                $avatar = gdGetAvatar($author, GD_AVATAR_SIZE_16);
                return array(
                    'src' => $avatar['src'],
                    'w' => $avatar['size'],
                    'h' => $avatar['size']
                );

            case self::COMPONENT_LAYOUT_PAGETABS_PAGE:
            case self::COMPONENT_LAYOUT_PAGETABS_SINGLE:
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

        return parent::getThumb($component, $props);
    }
    // protected function getPretitle(array $component, array &$props)
    // {
    //     $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
    //     switch ($component[1]) {
    //         case self::COMPONENT_LAYOUT_PAGETABS_AUTHOR:
    //         case self::COMPONENT_LAYOUT_PAGETABS_SINGLE:
    //             $natures = array(
    //                 self::COMPONENT_LAYOUT_PAGETABS_AUTHOR => UserRequestNature::USER,
    //                 self::COMPONENT_LAYOUT_PAGETABS_SINGLE => CustomPostRequestNature::CUSTOMPOST,
    //             );

    //             // For the default page add the thumbnail. For the others, add the pretitle
    //             $page_id = RequestUtils::getRoute();
    //             if ($page_id != RequestUtils::getNatureDefaultPage($natures[$component[1]] ?? null)) {
    //                 return $cmsengineapi->getTitle($page_id);
    //             }
    //             break;
    //     }

    //     return parent::getPretitle($component, $props);
    // }
    protected function getTitle(array $component, array &$props)
    {
        $userTypeAPI = UserTypeAPIFacade::getInstance();
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        $applicationtaxonomyapi = \PoP\ApplicationTaxonomies\FunctionAPIFactory::getInstance();
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_PAGETABS_AUTHOR:
                $author = \PoP\Root\App::getState(['routing', 'queried-object-id']);
                return $userTypeAPI->getUserDisplayName($author);

            case self::COMPONENT_LAYOUT_PAGETABS_ROUTE:
                $route = \PoP\Root\App::getState('route');
                return RouteUtils::getRouteTitle($route);

            case self::COMPONENT_LAYOUT_PAGETABS_PAGE:
            case self::COMPONENT_LAYOUT_PAGETABS_SINGLE:
                $customPostID = \PoP\Root\App::getState(['routing', 'queried-object-id']);
                return $customPostTypeAPI->getTitle($customPostID);

            case self::COMPONENT_LAYOUT_PAGETABS_TAG:
                $tag_id = \PoP\Root\App::getState(['routing', 'queried-object-id']);
                return $applicationtaxonomyapi->getTagSymbolName($tag_id);
        }

        $titles = array(
            self::COMPONENT_LAYOUT_PAGETABS_HOME => TranslationAPIFacade::getInstance()->__('Home', 'poptheme-wassup'),
            self::COMPONENT_LAYOUT_PAGETABS_404 => TranslationAPIFacade::getInstance()->__('Page not found!', 'poptheme-wassup'),
        );
        if ($title = $titles[$component[1]] ?? null) {
            return $title;
        }
        return parent::getTitle($component, $props);
    }
}


