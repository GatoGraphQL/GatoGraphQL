<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_Custom_EM_Module_Processor_MultiSelectFormInputs extends PoP_Module_Processor_MultiSelectFormInputsBase
{
    public const MODULE_FORMINPUT_LOCATIONPOSTCATEGORIES = 'forminput-locationpostcategories';
    public const MODULE_FILTERINPUT_LOCATIONPOSTCATEGORIES = 'filterinput-locationpostcategories';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINPUT_LOCATIONPOSTCATEGORIES],
            [self::class, self::MODULE_FILTERINPUT_LOCATIONPOSTCATEGORIES],
        );
    }

    // public function isFiltercomponent(array $module)
    // {
    //     switch ($module[1]) {
    //         case self::MODULE_FILTERINPUT_LOCATIONPOSTCATEGORIES:
    //             return true;
    //     }
        
    //     return parent::isFiltercomponent($module);
    // }

    public function getLabelText(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUT_LOCATIONPOSTCATEGORIES:
            case self::MODULE_FILTERINPUT_LOCATIONPOSTCATEGORIES:
                return TranslationAPIFacade::getInstance()->__('Categories', 'pop-locationposts-processors');
        }
        
        return parent::getLabelText($module, $props);
    }

    public function getInputClass(array $module): string
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUT_LOCATIONPOSTCATEGORIES:
            case self::MODULE_FILTERINPUT_LOCATIONPOSTCATEGORIES:
                return GD_FormInput_LocationPostCategories::class;
        }
        
        return parent::getInputClass($module);
    }

    public function getDbobjectField(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUT_LOCATIONPOSTCATEGORIES:
                return 'locationpostcategories';
        }
        
        return parent::getDbobjectField($module);
    }

    public function getName(array $module): string
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUT_LOCATIONPOSTCATEGORIES:
                return 'categories';
        }
        
        return parent::getName($module);
    }
}



