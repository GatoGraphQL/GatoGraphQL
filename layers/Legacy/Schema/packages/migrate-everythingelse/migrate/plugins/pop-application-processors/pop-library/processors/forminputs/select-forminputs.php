<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_Custom_Module_Processor_SelectFormInputs extends PoP_Module_Processor_BooleanSelectFormInputsBase
{
    public const MODULE_FORMINPUT_VOLUNTEERSNEEDED_SELECT = 'forminput-custom-volunteersneeded';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINPUT_VOLUNTEERSNEEDED_SELECT],
        );
    }

    public function getLabelText(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUT_VOLUNTEERSNEEDED_SELECT:
                return TranslationAPIFacade::getInstance()->__('Volunteers Needed?', 'poptheme-wassup');
        }

        return parent::getLabelText($module, $props);
    }

    public function getInputClass(array $module): string
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUT_VOLUNTEERSNEEDED_SELECT:
                return GD_FormInput_YesNo::class;
        }

        return parent::getInputClass($module);
    }

    public function getDbobjectField(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUT_VOLUNTEERSNEEDED_SELECT:
                return 'volunteersNeeded';
        }

        return parent::getDbobjectField($module);
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUT_VOLUNTEERSNEEDED_SELECT:
                // By default, set it on "No"
                $this->setProp($module, $props, 'default-value', false);
                break;
        }

        parent::initModelProps($module, $props);
    }
}



