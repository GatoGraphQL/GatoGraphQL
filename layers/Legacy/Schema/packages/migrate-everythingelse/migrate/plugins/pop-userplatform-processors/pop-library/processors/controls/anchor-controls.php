<?php
use PoP\Engine\Route\RouteUtils;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_UserPlatform_Module_Processor_AnchorControls extends PoP_Module_Processor_AnchorControlsBase
{
    public final const MODULE_ANCHORCONTROL_INVITENEWUSERS = 'anchorcontrol-invitenewusers';
    public final const MODULE_ANCHORCONTROL_SHARE_INVITENEWUSERS = 'anchorcontrol-share-invitenewusers';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_ANCHORCONTROL_INVITENEWUSERS],
            [self::class, self::MODULE_ANCHORCONTROL_SHARE_INVITENEWUSERS],
        );
    }

    public function getLabel(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_ANCHORCONTROL_SHARE_INVITENEWUSERS:
                return TranslationAPIFacade::getInstance()->__('Email', 'pop-coreprocessors');

            case self::MODULE_ANCHORCONTROL_INVITENEWUSERS:
                return TranslationAPIFacade::getInstance()->__('Invite your friends to join!', 'pop-coreprocessors');
        }

        return parent::getLabel($component, $props);
    }
    public function getFontawesome(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_ANCHORCONTROL_SHARE_INVITENEWUSERS:
                return 'fa-envelope';

            case self::MODULE_ANCHORCONTROL_INVITENEWUSERS:
                return 'fa-user-plus';
        }

        return parent::getFontawesome($component, $props);
    }
    public function getHref(array $component, array &$props)
    {
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        switch ($component[1]) {
            case self::MODULE_ANCHORCONTROL_INVITENEWUSERS:
            case self::MODULE_ANCHORCONTROL_SHARE_INVITENEWUSERS:
                return RouteUtils::getRouteURL(POP_USERPLATFORM_ROUTE_INVITENEWUSERS);
        }

        return parent::getHref($component, $props);
    }

    public function getTarget(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_ANCHORCONTROL_INVITENEWUSERS:
            case self::MODULE_ANCHORCONTROL_SHARE_INVITENEWUSERS:
                return POP_TARGET_MODALS;
        }

        return parent::getTarget($component, $props);
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::MODULE_ANCHORCONTROL_INVITENEWUSERS:
                $this->appendProp($component, $props, 'class', 'btn btn-default btn-block btn-invitenewusers');
                break;
        }

        parent::initModelProps($component, $props);
    }
}


