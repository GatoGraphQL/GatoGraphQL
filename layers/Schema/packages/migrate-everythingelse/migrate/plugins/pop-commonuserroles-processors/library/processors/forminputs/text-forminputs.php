<?php
use PoP\Translation\Facades\TranslationAPIFacade;

class GD_URE_Module_Processor_TextFormInputs extends PoP_Module_Processor_TextFormInputsBase
{
    public const MODULE_URE_FORMINPUT_CUP_CONTACTPERSON = 'forminput-ure-cup-contactperson';
    public const MODULE_URE_FORMINPUT_CUP_CONTACTNUMBER = 'forminput-ure-cup-contactnumber';
    public const MODULE_URE_FORMINPUT_CUP_LASTNAME = 'forminput-ure-cup-lastname';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_URE_FORMINPUT_CUP_LASTNAME],
            [self::class, self::MODULE_URE_FORMINPUT_CUP_CONTACTPERSON],
            [self::class, self::MODULE_URE_FORMINPUT_CUP_CONTACTNUMBER],
        );
    }

    public function getLabelText(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_URE_FORMINPUT_CUP_LASTNAME:
                return TranslationAPIFacade::getInstance()->__('Last name', 'ure-popprocessors');

            case self::MODULE_URE_FORMINPUT_CUP_CONTACTPERSON:
                return TranslationAPIFacade::getInstance()->__('Contact person', 'ure-popprocessors');

            case self::MODULE_URE_FORMINPUT_CUP_CONTACTNUMBER:
                return TranslationAPIFacade::getInstance()->__('Telephone / Fax', 'ure-popprocessors');
        }
        
        return parent::getLabelText($module, $props);
    }

    public function getLabel(array $module, array &$props)
    {
        $ret = parent::getLabel($module, $props);

        switch ($module[1]) {
            case self::MODULE_URE_FORMINPUT_CUP_CONTACTPERSON:
                return '<i class="fa fa-fw fa-user"></i>'.$ret;

            case self::MODULE_URE_FORMINPUT_CUP_CONTACTNUMBER:
                return '<i class="fa fa-fw fa-phone"></i>'.$ret;
        }
        
        return $ret;
    }

    public function getDbobjectField(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_URE_FORMINPUT_CUP_LASTNAME:
                return 'lastname';

            case self::MODULE_URE_FORMINPUT_CUP_CONTACTPERSON:
                return 'contactPerson';

            case self::MODULE_URE_FORMINPUT_CUP_CONTACTNUMBER:
                return 'contactNumber';
        }
        
        return parent::getDbobjectField($module);
    }
}



