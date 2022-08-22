<?php
use PoP\Engine\Route\RouteUtils;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\PostTags\ModuleConfiguration as PostTagsModuleConfiguration;

class PoP_Module_Processor_CustomAnchorControls extends PoP_Module_Processor_AnchorControlsBase
{
    public final const COMPONENT_ANCHORCONTROL_ADDPOST = 'buttoncontrol-addpost';
    public final const COMPONENT_ANCHORCONTROL_TAGSLINK = 'buttoncontrol-tagslink';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_ANCHORCONTROL_ADDPOST,
            self::COMPONENT_ANCHORCONTROL_TAGSLINK,
        );
    }

    public function getLabel(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_ANCHORCONTROL_ADDPOST:
                return TranslationAPIFacade::getInstance()->__('Add Post', 'poptheme-wassup');

            case self::COMPONENT_ANCHORCONTROL_TAGSLINK:
                return TranslationAPIFacade::getInstance()->__('View all tags', 'poptheme-wassup');
        }

        return parent::getLabel($component, $props);
    }
    public function getFontawesome(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_ANCHORCONTROL_ADDPOST:
                return 'fa-plus';

            case self::COMPONENT_ANCHORCONTROL_TAGSLINK:
                return 'fa-hashtag';
        }

        return parent::getFontawesome($component, $props);
    }
    public function getHref(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        switch ($component->name) {
            case self::COMPONENT_ANCHORCONTROL_ADDPOST:
            case self::COMPONENT_ANCHORCONTROL_TAGSLINK:
                $routes = array(
                    self::COMPONENT_ANCHORCONTROL_ADDPOST => POP_POSTSCREATION_ROUTE_ADDPOST,
                    self::COMPONENT_ANCHORCONTROL_TAGSLINK => PostTagsModuleConfiguration::getPostTagsRoute(),
                );
                $route = $routes[$component->name];

                return RouteUtils::getRouteURL($route);
        }

        return parent::getHref($component, $props);
    }
    public function getTarget(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_ANCHORCONTROL_ADDPOST:
                if (PoP_Application_Utils::getAddcontentTarget() == POP_TARGET_ADDONS) {
                    return POP_TARGET_ADDONS;
                }
                break;
        }

        return parent::getTarget($component, $props);
    }
    public function getText(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_ANCHORCONTROL_TAGSLINK:
                return null;
        }

        return parent::getText($component, $props);
    }
    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_ANCHORCONTROL_ADDPOST:
                $this->appendProp($component, $props, 'class', 'btn btn-primary');
                break;

            case self::COMPONENT_ANCHORCONTROL_TAGSLINK:
                $this->appendProp($component, $props, 'class', 'btn btn-link btn-compact');
                break;
        }

        parent::initModelProps($component, $props);
    }
}


