<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_CreateUpdatePostSelectFormInputs extends PoP_Module_Processor_SelectFormInputsBase
{
    public final const MODULE_FORMINPUT_CUP_STATUS = 'forminput-cup-status';
    public final const MODULE_CONTENTPOSTLINKS_FORMINPUT_LINKACCESS = 'forminput-linkaccess';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINPUT_CUP_STATUS],
            [self::class, self::MODULE_CONTENTPOSTLINKS_FORMINPUT_LINKACCESS],
        );
    }

    public function getLabelText(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_FORMINPUT_CUP_STATUS:
                return TranslationAPIFacade::getInstance()->__('Publishing status', 'poptheme-wassup');

            case self::MODULE_CONTENTPOSTLINKS_FORMINPUT_LINKACCESS:
                return TranslationAPIFacade::getInstance()->__('Access type', 'poptheme-wassup');
        }

        return parent::getLabelText($component, $props);
    }

    public function getInputClass(array $component): string
    {
        switch ($component[1]) {
            case self::MODULE_FORMINPUT_CUP_STATUS:
                return GD_FormInput_ModeratedStatusDescription::class;

            case self::MODULE_CONTENTPOSTLINKS_FORMINPUT_LINKACCESS:
                return GD_FormInput_LinkAccessDescription::class;
        }

        return parent::getInputClass($component);
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::MODULE_FORMINPUT_CUP_STATUS:
                $this->appendProp($component, $props, 'class', 'form-input-status');
                break;
        }

        parent::initModelProps($component, $props);
    }

    public function getDbobjectField(array $component): ?string
    {
        switch ($component[1]) {
            case self::MODULE_FORMINPUT_CUP_STATUS:
                return 'status';

            case self::MODULE_CONTENTPOSTLINKS_FORMINPUT_LINKACCESS:
                return 'linkaccess';
        }

        return parent::getDbobjectField($component);
    }

    public function getJsmethods(array $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);

        switch ($component[1]) {
            case self::MODULE_FORMINPUT_CUP_STATUS:
                $this->addJsmethod($ret, 'manageStatus');
                break;
        }

        return $ret;
    }
}



