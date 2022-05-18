<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_Custom_Module_Processor_SelectFormInputs extends PoP_Module_Processor_BooleanSelectFormInputsBase
{
    public final const MODULE_FORMINPUT_VOLUNTEERSNEEDED_SELECT = 'forminput-custom-volunteersneeded';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FORMINPUT_VOLUNTEERSNEEDED_SELECT],
        );
    }

    public function getLabelText(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMINPUT_VOLUNTEERSNEEDED_SELECT:
                return TranslationAPIFacade::getInstance()->__('Volunteers Needed?', 'poptheme-wassup');
        }

        return parent::getLabelText($component, $props);
    }

    public function getInputClass(array $component): string
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMINPUT_VOLUNTEERSNEEDED_SELECT:
                return GD_FormInput_YesNo::class;
        }

        return parent::getInputClass($component);
    }

    public function getDbobjectField(array $component): ?string
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMINPUT_VOLUNTEERSNEEDED_SELECT:
                return 'volunteersNeeded';
        }

        return parent::getDbobjectField($component);
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMINPUT_VOLUNTEERSNEEDED_SELECT:
                // By default, set it on "No"
                $this->setProp($component, $props, 'default-value', false);
                break;
        }

        parent::initModelProps($component, $props);
    }
}



