<?php
use PoP\Engine\Route\RouteUtils;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_UserPlatform_Module_Processor_AnchorControls extends PoP_Module_Processor_AnchorControlsBase
{
    public final const COMPONENT_ANCHORCONTROL_INVITENEWUSERS = 'anchorcontrol-invitenewusers';
    public final const COMPONENT_ANCHORCONTROL_SHARE_INVITENEWUSERS = 'anchorcontrol-share-invitenewusers';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_ANCHORCONTROL_INVITENEWUSERS,
            self::COMPONENT_ANCHORCONTROL_SHARE_INVITENEWUSERS,
        );
    }

    public function getLabel(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_ANCHORCONTROL_SHARE_INVITENEWUSERS:
                return TranslationAPIFacade::getInstance()->__('Email', 'pop-coreprocessors');

            case self::COMPONENT_ANCHORCONTROL_INVITENEWUSERS:
                return TranslationAPIFacade::getInstance()->__('Invite your friends to join!', 'pop-coreprocessors');
        }

        return parent::getLabel($component, $props);
    }
    public function getFontawesome(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_ANCHORCONTROL_SHARE_INVITENEWUSERS:
                return 'fa-envelope';

            case self::COMPONENT_ANCHORCONTROL_INVITENEWUSERS:
                return 'fa-user-plus';
        }

        return parent::getFontawesome($component, $props);
    }
    public function getHref(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        switch ($component->name) {
            case self::COMPONENT_ANCHORCONTROL_INVITENEWUSERS:
            case self::COMPONENT_ANCHORCONTROL_SHARE_INVITENEWUSERS:
                return RouteUtils::getRouteURL(POP_USERPLATFORM_ROUTE_INVITENEWUSERS);
        }

        return parent::getHref($component, $props);
    }

    public function getTarget(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_ANCHORCONTROL_INVITENEWUSERS:
            case self::COMPONENT_ANCHORCONTROL_SHARE_INVITENEWUSERS:
                return POP_TARGET_MODALS;
        }

        return parent::getTarget($component, $props);
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_ANCHORCONTROL_INVITENEWUSERS:
                $this->appendProp($component, $props, 'class', 'btn btn-default btn-block btn-invitenewusers');
                break;
        }

        parent::initModelProps($component, $props);
    }
}


