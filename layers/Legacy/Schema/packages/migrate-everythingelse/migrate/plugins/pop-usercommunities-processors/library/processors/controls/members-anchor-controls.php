<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\Engine\Route\RouteUtils;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_URE_Module_Processor_CustomAnchorControls extends PoP_Module_Processor_AnchorControlsBase
{
    public final const MODULE_ANCHORCONTROL_INVITENEWMEMBERS = 'anchorcontrol-invitenewmembers';
    public final const MODULE_ANCHORCONTROL_INVITENEWMEMBERS_BIG = 'anchorcontrol-invitenewmembers-big';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_ANCHORCONTROL_INVITENEWMEMBERS],
            [self::class, self::MODULE_ANCHORCONTROL_INVITENEWMEMBERS_BIG],
        );
    }

    public function getLabel(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_ANCHORCONTROL_INVITENEWMEMBERS:
            case self::MODULE_ANCHORCONTROL_INVITENEWMEMBERS_BIG:
                return TranslationAPIFacade::getInstance()->__('Invite new members', 'poptheme-wassup');
        }

        return parent::getLabel($component, $props);
    }
    public function getText(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_ANCHORCONTROL_INVITENEWMEMBERS:
                return null;
        }

        return parent::getText($component, $props);
    }
    public function getFontawesome(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_ANCHORCONTROL_INVITENEWMEMBERS:
            case self::MODULE_ANCHORCONTROL_INVITENEWMEMBERS_BIG:
                return 'fa-user-plus';
        }

        return parent::getFontawesome($component, $props);
    }
    public function getHref(array $component, array &$props)
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();

        switch ($component[1]) {
            case self::MODULE_ANCHORCONTROL_INVITENEWMEMBERS:
            case self::MODULE_ANCHORCONTROL_INVITENEWMEMBERS_BIG:
                return RouteUtils::getRouteURL(POP_USERCOMMUNITIES_ROUTE_INVITENEWMEMBERS);
        }

        return parent::getHref($component, $props);
    }

    public function getTarget(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_ANCHORCONTROL_INVITENEWMEMBERS:
            case self::MODULE_ANCHORCONTROL_INVITENEWMEMBERS_BIG:
                return POP_TARGET_MODALS;
        }

        return parent::getTarget($component, $props);
    }

    public function initModelProps(array $component, array &$props): void
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        switch ($component[1]) {
            case self::MODULE_ANCHORCONTROL_INVITENEWMEMBERS:
                $this->appendProp($component, $props, 'class', 'btn btn-compact btn-link');
                break;

            case self::MODULE_ANCHORCONTROL_INVITENEWMEMBERS_BIG:
                $this->appendProp($component, $props, 'class', 'btn btn-success btn-important btn-block');
                $this->setProp($component, $props, 'make-title', true);
                break;
        }

        parent::initModelProps($component, $props);
    }

    public function getClasses(array $component)
    {
        $ret = parent::getClasses($component);

        switch ($component[1]) {
            case self::MODULE_ANCHORCONTROL_INVITENEWMEMBERS_BIG:
                $ret[GD_JS_CLASSES]['text'] = '';
                break;
        }

        return $ret;
    }
}


