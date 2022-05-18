<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_Core_Bootstrap_Module_Processor_AnchorControls extends PoP_Module_Processor_AnchorControlsBase
{
    public final const MODULE_ANCHORCONTROL_EMBED = 'anchorcontrol-embed';
    public final const MODULE_ANCHORCONTROL_API = 'anchorcontrol-api';
    public final const MODULE_ANCHORCONTROL_COPYSEARCHURL = 'anchorcontrol-copysearchurl';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_ANCHORCONTROL_EMBED],
            [self::class, self::MODULE_ANCHORCONTROL_API],
            [self::class, self::MODULE_ANCHORCONTROL_COPYSEARCHURL],
        );
    }

    public function getLabel(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_ANCHORCONTROL_EMBED:
                return TranslationAPIFacade::getInstance()->__('Embed', 'pop-coreprocessors');

            case self::MODULE_ANCHORCONTROL_API:
                return TranslationAPIFacade::getInstance()->__('API Data', 'pop-coreprocessors');

            case self::MODULE_ANCHORCONTROL_COPYSEARCHURL:
                return TranslationAPIFacade::getInstance()->__('Copy Search URL', 'pop-coreprocessors');
        }

        return parent::getLabel($component, $props);
    }
    public function getFontawesome(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_ANCHORCONTROL_EMBED:
                return 'fa-code';

            case self::MODULE_ANCHORCONTROL_API:
                return 'fa-cog';

            case self::MODULE_ANCHORCONTROL_COPYSEARCHURL:
                return 'fa-link';
        }

        return parent::getFontawesome($component, $props);
    }
    public function getHref(array $component, array &$props)
    {

        // If PoP Engine Web Platform is not defined, then there is no `getFrontendId`
        if (defined('POP_ENGINEWEBPLATFORM_INITIALIZED')) {
            $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
            switch ($component[1]) {
                case self::MODULE_ANCHORCONTROL_EMBED:
                case self::MODULE_ANCHORCONTROL_API:
                case self::MODULE_ANCHORCONTROL_COPYSEARCHURL:
                    $modals = array(
                        self::MODULE_ANCHORCONTROL_EMBED => [PoP_Module_Processor_ShareModalComponents::class, PoP_Module_Processor_ShareModalComponents::MODULE_MODAL_EMBED],
                        self::MODULE_ANCHORCONTROL_API => [PoP_Module_Processor_ShareModalComponents::class, PoP_Module_Processor_ShareModalComponents::MODULE_MODAL_API],
                        self::MODULE_ANCHORCONTROL_COPYSEARCHURL => [PoP_Module_Processor_ShareModalComponents::class, PoP_Module_Processor_ShareModalComponents::MODULE_MODAL_COPYSEARCHURL],
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
            case self::MODULE_ANCHORCONTROL_EMBED:
            case self::MODULE_ANCHORCONTROL_API:
            case self::MODULE_ANCHORCONTROL_COPYSEARCHURL:
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


