<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_Custom_Module_Processor_SelectFormInputs extends PoP_Module_Processor_BooleanSelectFormInputsBase
{
    public final const COMPONENT_FORMINPUT_VOLUNTEERSNEEDED_SELECT = 'forminput-custom-volunteersneeded';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FORMINPUT_VOLUNTEERSNEEDED_SELECT,
        );
    }

    public function getLabelText(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_FORMINPUT_VOLUNTEERSNEEDED_SELECT:
                return TranslationAPIFacade::getInstance()->__('Volunteers Needed?', 'poptheme-wassup');
        }

        return parent::getLabelText($component, $props);
    }

    public function getInputClass(\PoP\ComponentModel\Component\Component $component): string
    {
        switch ($component->name) {
            case self::COMPONENT_FORMINPUT_VOLUNTEERSNEEDED_SELECT:
                return GD_FormInput_YesNo::class;
        }

        return parent::getInputClass($component);
    }

    public function getDbobjectField(\PoP\ComponentModel\Component\Component $component): ?string
    {
        switch ($component->name) {
            case self::COMPONENT_FORMINPUT_VOLUNTEERSNEEDED_SELECT:
                return 'volunteersNeeded';
        }

        return parent::getDbobjectField($component);
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_FORMINPUT_VOLUNTEERSNEEDED_SELECT:
                // By default, set it on "No"
                $this->setProp($component, $props, 'default-value', false);
                break;
        }

        parent::initModelProps($component, $props);
    }
}



