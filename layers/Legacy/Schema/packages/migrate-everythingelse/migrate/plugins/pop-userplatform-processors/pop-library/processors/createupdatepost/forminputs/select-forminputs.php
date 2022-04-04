<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_CreateUpdatePostSelectFormInputs extends PoP_Module_Processor_SelectFormInputsBase
{
    public final const MODULE_FORMINPUT_CUP_STATUS = 'forminput-cup-status';
    public final const MODULE_CONTENTPOSTLINKS_FORMINPUT_LINKACCESS = 'forminput-linkaccess';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINPUT_CUP_STATUS],
            [self::class, self::MODULE_CONTENTPOSTLINKS_FORMINPUT_LINKACCESS],
        );
    }

    public function getLabelText(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUT_CUP_STATUS:
                return TranslationAPIFacade::getInstance()->__('Publishing status', 'poptheme-wassup');

            case self::MODULE_CONTENTPOSTLINKS_FORMINPUT_LINKACCESS:
                return TranslationAPIFacade::getInstance()->__('Access type', 'poptheme-wassup');
        }

        return parent::getLabelText($module, $props);
    }

    public function getInputClass(array $module): string
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUT_CUP_STATUS:
                return GD_FormInput_ModeratedStatusDescription::class;

            case self::MODULE_CONTENTPOSTLINKS_FORMINPUT_LINKACCESS:
                return GD_FormInput_LinkAccessDescription::class;
        }

        return parent::getInputClass($module);
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUT_CUP_STATUS:
                $this->appendProp($module, $props, 'class', 'form-input-status');
                break;
        }

        parent::initModelProps($module, $props);
    }

    public function getDbobjectField(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUT_CUP_STATUS:
                return 'status';

            case self::MODULE_CONTENTPOSTLINKS_FORMINPUT_LINKACCESS:
                return 'linkaccess';
        }

        return parent::getDbobjectField($module);
    }

    public function getJsmethods(array $module, array &$props)
    {
        $ret = parent::getJsmethods($module, $props);

        switch ($module[1]) {
            case self::MODULE_FORMINPUT_CUP_STATUS:
                $this->addJsmethod($ret, 'manageStatus');
                break;
        }

        return $ret;
    }
}



