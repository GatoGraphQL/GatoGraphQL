<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_ContentCreation_Module_Processor_Buttons extends PoP_Module_Processor_ButtonsBase
{
    public final const COMPONENT_BUTTON_POSTEDIT = 'button-postedit';
    public final const COMPONENT_BUTTON_POSTVIEW = 'button-postview';
    public final const COMPONENT_BUTTON_POSTPREVIEW = 'button-postpreview';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_BUTTON_POSTEDIT,
            self::COMPONENT_BUTTON_POSTVIEW,
            self::COMPONENT_BUTTON_POSTPREVIEW,
        );
    }

    public function getButtoninnerSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_BUTTON_POSTEDIT:
                return [PoP_ContentCreation_Module_Processor_ButtonInners::class, PoP_ContentCreation_Module_Processor_ButtonInners::COMPONENT_BUTTONINNER_POSTEDIT];

            case self::COMPONENT_BUTTON_POSTVIEW:
                return [PoP_ContentCreation_Module_Processor_ButtonInners::class, PoP_ContentCreation_Module_Processor_ButtonInners::COMPONENT_BUTTONINNER_POSTVIEW];

            case self::COMPONENT_BUTTON_POSTPREVIEW:
                return [PoP_ContentCreation_Module_Processor_ButtonInners::class, PoP_ContentCreation_Module_Processor_ButtonInners::COMPONENT_BUTTONINNER_POSTPREVIEW];
        }

        return parent::getButtoninnerSubcomponent($component);
    }

    public function getUrlField(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_BUTTON_POSTEDIT:
                return 'editURL';

            case self::COMPONENT_BUTTON_POSTPREVIEW:
                return 'previewURL';
        }

        return parent::getUrlField($component);
    }

    public function getTitle(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_BUTTON_POSTEDIT:
                return TranslationAPIFacade::getInstance()->__('Edit', 'pop-coreprocessors');

            case self::COMPONENT_BUTTON_POSTVIEW:
                return TranslationAPIFacade::getInstance()->__('View', 'pop-coreprocessors');

            case self::COMPONENT_BUTTON_POSTPREVIEW:
                return TranslationAPIFacade::getInstance()->__('Preview', 'pop-coreprocessors');
        }

        return parent::getTitle($component, $props);
    }

    public function getLinktarget(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_BUTTON_POSTPREVIEW:
                return PoP_Application_Utils::getPreviewTarget();
        }

        return parent::getLinktarget($component, $props);
    }

    public function getBtnClass(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $ret = parent::getBtnClass($component, $props);

        switch ($component->name) {
            case self::COMPONENT_BUTTON_POSTEDIT:
            case self::COMPONENT_BUTTON_POSTVIEW:
            case self::COMPONENT_BUTTON_POSTPREVIEW:
                $ret .= ' btn btn-xs btn-default';
                break;
        }

        return $ret;
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_BUTTON_POSTPREVIEW:
                // Allow to add data-sw-networkfirst="true"
                if ($params = \PoP\Root\App::applyFilters('GD_ContentCreation_Module_Processor_Buttons:postpreview:params', array())) {
                    $this->mergeProp($component, $props, 'params', $params);
                }
                break;
        }

        parent::initModelProps($component, $props);
    }
}


