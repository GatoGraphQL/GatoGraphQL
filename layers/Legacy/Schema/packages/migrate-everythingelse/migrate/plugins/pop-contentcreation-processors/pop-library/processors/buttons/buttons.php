<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_ContentCreation_Module_Processor_Buttons extends PoP_Module_Processor_ButtonsBase
{
    public final const MODULE_BUTTON_POSTEDIT = 'button-postedit';
    public final const MODULE_BUTTON_POSTVIEW = 'button-postview';
    public final const MODULE_BUTTON_POSTPREVIEW = 'button-postpreview';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BUTTON_POSTEDIT],
            [self::class, self::MODULE_BUTTON_POSTVIEW],
            [self::class, self::MODULE_BUTTON_POSTPREVIEW],
        );
    }

    public function getButtoninnerSubmodule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BUTTON_POSTEDIT:
                return [PoP_ContentCreation_Module_Processor_ButtonInners::class, PoP_ContentCreation_Module_Processor_ButtonInners::MODULE_BUTTONINNER_POSTEDIT];

            case self::MODULE_BUTTON_POSTVIEW:
                return [PoP_ContentCreation_Module_Processor_ButtonInners::class, PoP_ContentCreation_Module_Processor_ButtonInners::MODULE_BUTTONINNER_POSTVIEW];

            case self::MODULE_BUTTON_POSTPREVIEW:
                return [PoP_ContentCreation_Module_Processor_ButtonInners::class, PoP_ContentCreation_Module_Processor_ButtonInners::MODULE_BUTTONINNER_POSTPREVIEW];
        }

        return parent::getButtoninnerSubmodule($componentVariation);
    }

    public function getUrlField(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BUTTON_POSTEDIT:
                return 'editURL';

            case self::MODULE_BUTTON_POSTPREVIEW:
                return 'previewURL';
        }

        return parent::getUrlField($componentVariation);
    }

    public function getTitle(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BUTTON_POSTEDIT:
                return TranslationAPIFacade::getInstance()->__('Edit', 'pop-coreprocessors');

            case self::MODULE_BUTTON_POSTVIEW:
                return TranslationAPIFacade::getInstance()->__('View', 'pop-coreprocessors');

            case self::MODULE_BUTTON_POSTPREVIEW:
                return TranslationAPIFacade::getInstance()->__('Preview', 'pop-coreprocessors');
        }

        return parent::getTitle($componentVariation, $props);
    }

    public function getLinktarget(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BUTTON_POSTPREVIEW:
                return PoP_Application_Utils::getPreviewTarget();
        }

        return parent::getLinktarget($componentVariation, $props);
    }

    public function getBtnClass(array $componentVariation, array &$props)
    {
        $ret = parent::getBtnClass($componentVariation, $props);

        switch ($componentVariation[1]) {
            case self::MODULE_BUTTON_POSTEDIT:
            case self::MODULE_BUTTON_POSTVIEW:
            case self::MODULE_BUTTON_POSTPREVIEW:
                $ret .= ' btn btn-xs btn-default';
                break;
        }

        return $ret;
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BUTTON_POSTPREVIEW:
                // Allow to add data-sw-networkfirst="true"
                if ($params = \PoP\Root\App::applyFilters('GD_ContentCreation_Module_Processor_Buttons:postpreview:params', array())) {
                    $this->mergeProp($componentVariation, $props, 'params', $params);
                }
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}


