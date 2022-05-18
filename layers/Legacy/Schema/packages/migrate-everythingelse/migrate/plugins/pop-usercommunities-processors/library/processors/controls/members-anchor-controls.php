<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\Engine\Route\RouteUtils;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_URE_Module_Processor_CustomAnchorControls extends PoP_Module_Processor_AnchorControlsBase
{
    public final const MODULE_ANCHORCONTROL_INVITENEWMEMBERS = 'anchorcontrol-invitenewmembers';
    public final const MODULE_ANCHORCONTROL_INVITENEWMEMBERS_BIG = 'anchorcontrol-invitenewmembers-big';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_ANCHORCONTROL_INVITENEWMEMBERS],
            [self::class, self::MODULE_ANCHORCONTROL_INVITENEWMEMBERS_BIG],
        );
    }

    public function getLabel(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_ANCHORCONTROL_INVITENEWMEMBERS:
            case self::MODULE_ANCHORCONTROL_INVITENEWMEMBERS_BIG:
                return TranslationAPIFacade::getInstance()->__('Invite new members', 'poptheme-wassup');
        }

        return parent::getLabel($componentVariation, $props);
    }
    public function getText(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_ANCHORCONTROL_INVITENEWMEMBERS:
                return null;
        }

        return parent::getText($componentVariation, $props);
    }
    public function getFontawesome(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_ANCHORCONTROL_INVITENEWMEMBERS:
            case self::MODULE_ANCHORCONTROL_INVITENEWMEMBERS_BIG:
                return 'fa-user-plus';
        }

        return parent::getFontawesome($componentVariation, $props);
    }
    public function getHref(array $componentVariation, array &$props)
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();

        switch ($componentVariation[1]) {
            case self::MODULE_ANCHORCONTROL_INVITENEWMEMBERS:
            case self::MODULE_ANCHORCONTROL_INVITENEWMEMBERS_BIG:
                return RouteUtils::getRouteURL(POP_USERCOMMUNITIES_ROUTE_INVITENEWMEMBERS);
        }

        return parent::getHref($componentVariation, $props);
    }

    public function getTarget(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_ANCHORCONTROL_INVITENEWMEMBERS:
            case self::MODULE_ANCHORCONTROL_INVITENEWMEMBERS_BIG:
                return POP_TARGET_MODALS;
        }

        return parent::getTarget($componentVariation, $props);
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        switch ($componentVariation[1]) {
            case self::MODULE_ANCHORCONTROL_INVITENEWMEMBERS:
                $this->appendProp($componentVariation, $props, 'class', 'btn btn-compact btn-link');
                break;

            case self::MODULE_ANCHORCONTROL_INVITENEWMEMBERS_BIG:
                $this->appendProp($componentVariation, $props, 'class', 'btn btn-success btn-important btn-block');
                $this->setProp($componentVariation, $props, 'make-title', true);
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }

    public function getClasses(array $componentVariation)
    {
        $ret = parent::getClasses($componentVariation);

        switch ($componentVariation[1]) {
            case self::MODULE_ANCHORCONTROL_INVITENEWMEMBERS_BIG:
                $ret[GD_JS_CLASSES]['text'] = '';
                break;
        }

        return $ret;
    }
}


