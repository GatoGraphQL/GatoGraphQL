<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\Engine\Route\RouteUtils;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_URE_Module_Processor_CustomAnchorControls extends PoP_Module_Processor_AnchorControlsBase
{
    public final const MODULE_ANCHORCONTROL_INVITENEWMEMBERS = 'anchorcontrol-invitenewmembers';
    public final const MODULE_ANCHORCONTROL_INVITENEWMEMBERS_BIG = 'anchorcontrol-invitenewmembers-big';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_ANCHORCONTROL_INVITENEWMEMBERS],
            [self::class, self::MODULE_ANCHORCONTROL_INVITENEWMEMBERS_BIG],
        );
    }

    public function getLabel(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_ANCHORCONTROL_INVITENEWMEMBERS:
            case self::MODULE_ANCHORCONTROL_INVITENEWMEMBERS_BIG:
                return TranslationAPIFacade::getInstance()->__('Invite new members', 'poptheme-wassup');
        }

        return parent::getLabel($module, $props);
    }
    public function getText(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_ANCHORCONTROL_INVITENEWMEMBERS:
                return null;
        }

        return parent::getText($module, $props);
    }
    public function getFontawesome(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_ANCHORCONTROL_INVITENEWMEMBERS:
            case self::MODULE_ANCHORCONTROL_INVITENEWMEMBERS_BIG:
                return 'fa-user-plus';
        }

        return parent::getFontawesome($module, $props);
    }
    public function getHref(array $module, array &$props)
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();

        switch ($module[1]) {
            case self::MODULE_ANCHORCONTROL_INVITENEWMEMBERS:
            case self::MODULE_ANCHORCONTROL_INVITENEWMEMBERS_BIG:
                return RouteUtils::getRouteURL(POP_USERCOMMUNITIES_ROUTE_INVITENEWMEMBERS);
        }

        return parent::getHref($module, $props);
    }

    public function getTarget(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_ANCHORCONTROL_INVITENEWMEMBERS:
            case self::MODULE_ANCHORCONTROL_INVITENEWMEMBERS_BIG:
                return POP_TARGET_MODALS;
        }

        return parent::getTarget($module, $props);
    }

    public function initModelProps(array $module, array &$props): void
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        switch ($module[1]) {
            case self::MODULE_ANCHORCONTROL_INVITENEWMEMBERS:
                $this->appendProp($module, $props, 'class', 'btn btn-compact btn-link');
                break;

            case self::MODULE_ANCHORCONTROL_INVITENEWMEMBERS_BIG:
                $this->appendProp($module, $props, 'class', 'btn btn-success btn-important btn-block');
                $this->setProp($module, $props, 'make-title', true);
                break;
        }

        parent::initModelProps($module, $props);
    }

    public function getClasses(array $module)
    {
        $ret = parent::getClasses($module);

        switch ($module[1]) {
            case self::MODULE_ANCHORCONTROL_INVITENEWMEMBERS_BIG:
                $ret[GD_JS_CLASSES]['text'] = '';
                break;
        }

        return $ret;
    }
}


