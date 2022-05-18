<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_FeaturedImageFormComponents extends PoP_Module_Processor_FeaturedImageFormComponentsBase
{
    public final const MODULE_FORMCOMPONENT_FEATUREDIMAGE = 'formcomponent-featuredimage';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMCOMPONENT_FEATUREDIMAGE],
        );
    }

    public function getFeaturedimageinnerSubmodule(array $component): ?array
    {
        switch ($component[1]) {
            case self::MODULE_FORMCOMPONENT_FEATUREDIMAGE:
                return [PoP_Module_Processor_FeaturedImageInnerComponentInputs::class, PoP_Module_Processor_FeaturedImageInnerComponentInputs::MODULE_FORMINPUT_FEATUREDIMAGEINNER];
        }

        return null;
    }

    public function getLabelText(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_FORMCOMPONENT_FEATUREDIMAGE:
                return TranslationAPIFacade::getInstance()->__('Featured Image', 'pop-coreprocessors');
        }
        
        return parent::getLabelText($component, $props);
    }

    public function isMandatory(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_FORMCOMPONENT_FEATUREDIMAGE:
                return true;
        }
        
        return parent::isMandatory($component, $props);
    }
}



