<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoPCore_GenericForms_Module_Processor_AnchorControls extends PoP_Module_Processor_AnchorControlsBase
{
    public final const MODULE_ANCHORCONTROL_SHAREBYEMAIL = 'anchorcontrol-sharebyemail';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_ANCHORCONTROL_SHAREBYEMAIL],
        );
    }

    public function getLabel(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_ANCHORCONTROL_SHAREBYEMAIL:
                return TranslationAPIFacade::getInstance()->__('Share by email', 'pop-coreprocessors');
        }

        return parent::getLabel($component, $props);
    }
    public function getFontawesome(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_ANCHORCONTROL_SHAREBYEMAIL:
                return 'fa-envelope';
        }

        return parent::getFontawesome($component, $props);
    }
    public function getHref(array $component, array &$props)
    {

        // If PoP Engine Web Platform is not defined, then there is no `getFrontendId`
        if (defined('POP_ENGINEWEBPLATFORM_INITIALIZED')) {
            $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
            switch ($component[1]) {
                case self::COMPONENT_ANCHORCONTROL_SHAREBYEMAIL:
                    $modals = array(
                        self::COMPONENT_ANCHORCONTROL_SHAREBYEMAIL => [PoP_Module_Processor_GFModalComponents::class, PoP_Module_Processor_GFModalComponents::COMPONENT_MODAL_SHAREBYEMAIL],
                    );
                    $modal = $modals[$component[1]];
                    return '#'.$componentprocessor_manager->getProcessor($modal)->getFrontendId($modal, $props).'_modal';
            }
        }

        return parent::getHref($component, $props);
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_ANCHORCONTROL_SHAREBYEMAIL:
                $this->mergeProp(
                    $component,
                    $props,
                    'params',
                    array(
                        'data-toggle' => 'modal',
                        'data-blocktarget' => $this->getProp($component, $props, 'control-target'),
                        'data-target-title' => $this->getProp($component, $props, 'controltarget-title'),
                    )
                );
                break;
        }

        parent::initModelProps($component, $props);
    }
}


