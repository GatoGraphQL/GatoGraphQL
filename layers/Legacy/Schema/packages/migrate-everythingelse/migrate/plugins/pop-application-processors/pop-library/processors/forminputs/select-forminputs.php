<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_Custom_Module_Processor_SelectFormInputs extends PoP_Module_Processor_BooleanSelectFormInputsBase
{
    public final const MODULE_FORMINPUT_VOLUNTEERSNEEDED_SELECT = 'forminput-custom-volunteersneeded';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINPUT_VOLUNTEERSNEEDED_SELECT],
        );
    }

    public function getLabelText(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMINPUT_VOLUNTEERSNEEDED_SELECT:
                return TranslationAPIFacade::getInstance()->__('Volunteers Needed?', 'poptheme-wassup');
        }

        return parent::getLabelText($componentVariation, $props);
    }

    public function getInputClass(array $componentVariation): string
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMINPUT_VOLUNTEERSNEEDED_SELECT:
                return GD_FormInput_YesNo::class;
        }

        return parent::getInputClass($componentVariation);
    }

    public function getDbobjectField(array $componentVariation): ?string
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMINPUT_VOLUNTEERSNEEDED_SELECT:
                return 'volunteersNeeded';
        }

        return parent::getDbobjectField($componentVariation);
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMINPUT_VOLUNTEERSNEEDED_SELECT:
                // By default, set it on "No"
                $this->setProp($componentVariation, $props, 'default-value', false);
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}



