<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_CreateUpdatePostSelectFormInputs extends PoP_Module_Processor_SelectFormInputsBase
{
    public final const MODULE_FORMINPUT_CUP_STATUS = 'forminput-cup-status';
    public final const MODULE_CONTENTPOSTLINKS_FORMINPUT_LINKACCESS = 'forminput-linkaccess';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINPUT_CUP_STATUS],
            [self::class, self::MODULE_CONTENTPOSTLINKS_FORMINPUT_LINKACCESS],
        );
    }

    public function getLabelText(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMINPUT_CUP_STATUS:
                return TranslationAPIFacade::getInstance()->__('Publishing status', 'poptheme-wassup');

            case self::MODULE_CONTENTPOSTLINKS_FORMINPUT_LINKACCESS:
                return TranslationAPIFacade::getInstance()->__('Access type', 'poptheme-wassup');
        }

        return parent::getLabelText($componentVariation, $props);
    }

    public function getInputClass(array $componentVariation): string
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMINPUT_CUP_STATUS:
                return GD_FormInput_ModeratedStatusDescription::class;

            case self::MODULE_CONTENTPOSTLINKS_FORMINPUT_LINKACCESS:
                return GD_FormInput_LinkAccessDescription::class;
        }

        return parent::getInputClass($componentVariation);
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMINPUT_CUP_STATUS:
                $this->appendProp($componentVariation, $props, 'class', 'form-input-status');
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }

    public function getDbobjectField(array $componentVariation): ?string
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMINPUT_CUP_STATUS:
                return 'status';

            case self::MODULE_CONTENTPOSTLINKS_FORMINPUT_LINKACCESS:
                return 'linkaccess';
        }

        return parent::getDbobjectField($componentVariation);
    }

    public function getJsmethods(array $componentVariation, array &$props)
    {
        $ret = parent::getJsmethods($componentVariation, $props);

        switch ($componentVariation[1]) {
            case self::MODULE_FORMINPUT_CUP_STATUS:
                $this->addJsmethod($ret, 'manageStatus');
                break;
        }

        return $ret;
    }
}



