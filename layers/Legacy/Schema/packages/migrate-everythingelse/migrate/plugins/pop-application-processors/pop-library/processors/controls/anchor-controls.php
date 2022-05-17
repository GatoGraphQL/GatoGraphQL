<?php
use PoP\Engine\Route\RouteUtils;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\PostTags\ModuleConfiguration as PostTagsComponentConfiguration;

class PoP_Module_Processor_CustomAnchorControls extends PoP_Module_Processor_AnchorControlsBase
{
    public final const MODULE_ANCHORCONTROL_ADDPOST = 'buttoncontrol-addpost';
    public final const MODULE_ANCHORCONTROL_TAGSLINK = 'buttoncontrol-tagslink';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_ANCHORCONTROL_ADDPOST],
            [self::class, self::MODULE_ANCHORCONTROL_TAGSLINK],
        );
    }

    public function getLabel(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_ANCHORCONTROL_ADDPOST:
                return TranslationAPIFacade::getInstance()->__('Add Post', 'poptheme-wassup');

            case self::MODULE_ANCHORCONTROL_TAGSLINK:
                return TranslationAPIFacade::getInstance()->__('View all tags', 'poptheme-wassup');
        }

        return parent::getLabel($module, $props);
    }
    public function getFontawesome(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_ANCHORCONTROL_ADDPOST:
                return 'fa-plus';

            case self::MODULE_ANCHORCONTROL_TAGSLINK:
                return 'fa-hashtag';
        }

        return parent::getFontawesome($module, $props);
    }
    public function getHref(array $module, array &$props)
    {
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        switch ($module[1]) {
            case self::MODULE_ANCHORCONTROL_ADDPOST:
            case self::MODULE_ANCHORCONTROL_TAGSLINK:
                $routes = array(
                    self::MODULE_ANCHORCONTROL_ADDPOST => POP_POSTSCREATION_ROUTE_ADDPOST,
                    self::MODULE_ANCHORCONTROL_TAGSLINK => PostTagsComponentConfiguration::getPostTagsRoute(),
                );
                $route = $routes[$module[1]];

                return RouteUtils::getRouteURL($route);
        }

        return parent::getHref($module, $props);
    }
    public function getTarget(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_ANCHORCONTROL_ADDPOST:
                if (PoP_Application_Utils::getAddcontentTarget() == POP_TARGET_ADDONS) {
                    return POP_TARGET_ADDONS;
                }
                break;
        }

        return parent::getTarget($module, $props);
    }
    public function getText(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_ANCHORCONTROL_TAGSLINK:
                return null;
        }

        return parent::getText($module, $props);
    }
    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_ANCHORCONTROL_ADDPOST:
                $this->appendProp($module, $props, 'class', 'btn btn-primary');
                break;

            case self::MODULE_ANCHORCONTROL_TAGSLINK:
                $this->appendProp($module, $props, 'class', 'btn btn-link btn-compact');
                break;
        }

        parent::initModelProps($module, $props);
    }
}


