<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_Custom_EM_Module_Processor_MultiSelectFormInputs extends PoP_Module_Processor_MultiSelectFormInputsBase
{
    public final const MODULE_FORMINPUT_LOCATIONPOSTCATEGORIES = 'forminput-locationpostcategories';
    public final const MODULE_FILTERINPUT_LOCATIONPOSTCATEGORIES = 'filterinput-locationpostcategories';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINPUT_LOCATIONPOSTCATEGORIES],
            [self::class, self::MODULE_FILTERINPUT_LOCATIONPOSTCATEGORIES],
        );
    }

    // public function isFiltercomponent(array $componentVariation)
    // {
    //     switch ($componentVariation[1]) {
    //         case self::MODULE_FILTERINPUT_LOCATIONPOSTCATEGORIES:
    //             return true;
    //     }
        
    //     return parent::isFiltercomponent($componentVariation);
    // }

    public function getLabelText(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMINPUT_LOCATIONPOSTCATEGORIES:
            case self::MODULE_FILTERINPUT_LOCATIONPOSTCATEGORIES:
                return TranslationAPIFacade::getInstance()->__('Categories', 'pop-locationposts-processors');
        }
        
        return parent::getLabelText($componentVariation, $props);
    }

    public function getInputClass(array $componentVariation): string
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMINPUT_LOCATIONPOSTCATEGORIES:
            case self::MODULE_FILTERINPUT_LOCATIONPOSTCATEGORIES:
                return GD_FormInput_LocationPostCategories::class;
        }
        
        return parent::getInputClass($componentVariation);
    }

    public function getDbobjectField(array $componentVariation): ?string
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMINPUT_LOCATIONPOSTCATEGORIES:
                return 'locationpostcategories';
        }
        
        return parent::getDbobjectField($componentVariation);
    }

    public function getName(array $componentVariation): string
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMINPUT_LOCATIONPOSTCATEGORIES:
                return 'categories';
        }
        
        return parent::getName($componentVariation);
    }
}



