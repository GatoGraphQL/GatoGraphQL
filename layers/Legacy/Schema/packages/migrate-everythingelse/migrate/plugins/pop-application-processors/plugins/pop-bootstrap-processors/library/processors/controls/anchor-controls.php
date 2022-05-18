<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoPCore_GenericForms_Module_Processor_AnchorControls extends PoP_Module_Processor_AnchorControlsBase
{
    public final const MODULE_ANCHORCONTROL_SHAREBYEMAIL = 'anchorcontrol-sharebyemail';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_ANCHORCONTROL_SHAREBYEMAIL],
        );
    }

    public function getLabel(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_ANCHORCONTROL_SHAREBYEMAIL:
                return TranslationAPIFacade::getInstance()->__('Share by email', 'pop-coreprocessors');
        }

        return parent::getLabel($module, $props);
    }
    public function getFontawesome(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_ANCHORCONTROL_SHAREBYEMAIL:
                return 'fa-envelope';
        }

        return parent::getFontawesome($module, $props);
    }
    public function getHref(array $module, array &$props)
    {

        // If PoP Engine Web Platform is not defined, then there is no `getFrontendId`
        if (defined('POP_ENGINEWEBPLATFORM_INITIALIZED')) {
            $moduleprocessor_manager = ComponentProcessorManagerFacade::getInstance();
            switch ($module[1]) {
                case self::MODULE_ANCHORCONTROL_SHAREBYEMAIL:
                    $modals = array(
                        self::MODULE_ANCHORCONTROL_SHAREBYEMAIL => [PoP_Module_Processor_GFModalComponents::class, PoP_Module_Processor_GFModalComponents::MODULE_MODAL_SHAREBYEMAIL],
                    );
                    $modal = $modals[$module[1]];
                    return '#'.$moduleprocessor_manager->getProcessor($modal)->getFrontendId($modal, $props).'_modal';
            }
        }

        return parent::getHref($module, $props);
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_ANCHORCONTROL_SHAREBYEMAIL:
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


