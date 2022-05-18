<?php
use PoP\Engine\Route\RouteUtils;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\PostTags\ModuleConfiguration as PostTagsModuleConfiguration;

class PoP_Module_Processor_CustomAnchorControls extends PoP_Module_Processor_AnchorControlsBase
{
    public final const MODULE_ANCHORCONTROL_ADDPOST = 'buttoncontrol-addpost';
    public final const MODULE_ANCHORCONTROL_TAGSLINK = 'buttoncontrol-tagslink';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_ANCHORCONTROL_ADDPOST],
            [self::class, self::MODULE_ANCHORCONTROL_TAGSLINK],
        );
    }

    public function getLabel(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_ANCHORCONTROL_ADDPOST:
                return TranslationAPIFacade::getInstance()->__('Add Post', 'poptheme-wassup');

            case self::MODULE_ANCHORCONTROL_TAGSLINK:
                return TranslationAPIFacade::getInstance()->__('View all tags', 'poptheme-wassup');
        }

        return parent::getLabel($component, $props);
    }
    public function getFontawesome(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_ANCHORCONTROL_ADDPOST:
                return 'fa-plus';

            case self::MODULE_ANCHORCONTROL_TAGSLINK:
                return 'fa-hashtag';
        }

        return parent::getFontawesome($component, $props);
    }
    public function getHref(array $component, array &$props)
    {
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        switch ($component[1]) {
            case self::MODULE_ANCHORCONTROL_ADDPOST:
            case self::MODULE_ANCHORCONTROL_TAGSLINK:
                $routes = array(
                    self::MODULE_ANCHORCONTROL_ADDPOST => POP_POSTSCREATION_ROUTE_ADDPOST,
                    self::MODULE_ANCHORCONTROL_TAGSLINK => PostTagsModuleConfiguration::getPostTagsRoute(),
                );
                $route = $routes[$component[1]];

                return RouteUtils::getRouteURL($route);
        }

        return parent::getHref($component, $props);
    }
    public function getTarget(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_ANCHORCONTROL_ADDPOST:
                if (PoP_Application_Utils::getAddcontentTarget() == POP_TARGET_ADDONS) {
                    return POP_TARGET_ADDONS;
                }
                break;
        }

        return parent::getTarget($component, $props);
    }
    public function getText(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_ANCHORCONTROL_TAGSLINK:
                return null;
        }

        return parent::getText($component, $props);
    }
    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::MODULE_ANCHORCONTROL_ADDPOST:
                $this->appendProp($component, $props, 'class', 'btn btn-primary');
                break;

            case self::MODULE_ANCHORCONTROL_TAGSLINK:
                $this->appendProp($component, $props, 'class', 'btn btn-link btn-compact');
                break;
        }

        parent::initModelProps($component, $props);
    }
}


