<?php
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;

class GD_Core_Bootstrap_Module_Processor_AnchorControls extends PoP_Module_Processor_AnchorControlsBase
{
    public const MODULE_ANCHORCONTROL_EMBED = 'anchorcontrol-embed';
    public const MODULE_ANCHORCONTROL_API = 'anchorcontrol-api';
    public const MODULE_ANCHORCONTROL_COPYSEARCHURL = 'anchorcontrol-copysearchurl';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_ANCHORCONTROL_EMBED],
            [self::class, self::MODULE_ANCHORCONTROL_API],
            [self::class, self::MODULE_ANCHORCONTROL_COPYSEARCHURL],
        );
    }

    public function getLabel(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_ANCHORCONTROL_EMBED:
                return TranslationAPIFacade::getInstance()->__('Embed', 'pop-coreprocessors');

            case self::MODULE_ANCHORCONTROL_API:
                return TranslationAPIFacade::getInstance()->__('API Data', 'pop-coreprocessors');

            case self::MODULE_ANCHORCONTROL_COPYSEARCHURL:
                return TranslationAPIFacade::getInstance()->__('Copy Search URL', 'pop-coreprocessors');
        }

        return parent::getLabel($module, $props);
    }
    public function getFontawesome(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_ANCHORCONTROL_EMBED:
                return 'fa-code';

            case self::MODULE_ANCHORCONTROL_API:
                return 'fa-cog';

            case self::MODULE_ANCHORCONTROL_COPYSEARCHURL:
                return 'fa-link';
        }

        return parent::getFontawesome($module, $props);
    }
    public function getHref(array $module, array &$props)
    {

        // If PoP Engine Web Platform is not defined, then there is no `getFrontendId`
        if (defined('POP_ENGINEWEBPLATFORM_INITIALIZED')) {
            $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();
            switch ($module[1]) {
                case self::MODULE_ANCHORCONTROL_EMBED:
                case self::MODULE_ANCHORCONTROL_API:
                case self::MODULE_ANCHORCONTROL_COPYSEARCHURL:
                    $modals = array(
                        self::MODULE_ANCHORCONTROL_EMBED => [PoP_Module_Processor_ShareModalComponents::class, PoP_Module_Processor_ShareModalComponents::MODULE_MODAL_EMBED],
                        self::MODULE_ANCHORCONTROL_API => [PoP_Module_Processor_ShareModalComponents::class, PoP_Module_Processor_ShareModalComponents::MODULE_MODAL_API],
                        self::MODULE_ANCHORCONTROL_COPYSEARCHURL => [PoP_Module_Processor_ShareModalComponents::class, PoP_Module_Processor_ShareModalComponents::MODULE_MODAL_COPYSEARCHURL],
                    );
                    $modal = $modals[$module[1]];
                    return '#'.$moduleprocessor_manager->getProcessor($modal)->getFrontendId($modal, $props).'_modal';
            }
        }

        return parent::getHref($module, $props);
    }

    public function initModelProps(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_ANCHORCONTROL_EMBED:
            case self::MODULE_ANCHORCONTROL_API:
            case self::MODULE_ANCHORCONTROL_COPYSEARCHURL:
                $this->mergeProp(
                    $module,
                    $props,
                    'params',
                    array(
                        'data-toggle' => 'modal',
                        'data-blocktarget' => $this->getProp($module, $props, 'control-target'),
                        'data-target-title' => $this->getProp($module, $props, 'controltarget-title'),
                    )
                );
                break;
        }

        parent::initModelProps($module, $props);
    }
}


