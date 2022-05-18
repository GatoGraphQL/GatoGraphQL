<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_Custom_EM_Module_Processor_MultiSelectFormInputs extends PoP_Module_Processor_MultiSelectFormInputsBase
{
    public final const MODULE_FORMINPUT_LOCATIONPOSTCATEGORIES = 'forminput-locationpostcategories';
    public final const MODULE_FILTERINPUT_LOCATIONPOSTCATEGORIES = 'filterinput-locationpostcategories';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FORMINPUT_LOCATIONPOSTCATEGORIES],
            [self::class, self::COMPONENT_FILTERINPUT_LOCATIONPOSTCATEGORIES],
        );
    }

    // public function isFiltercomponent(array $component)
    // {
    //     switch ($component[1]) {
    //         case self::COMPONENT_FILTERINPUT_LOCATIONPOSTCATEGORIES:
    //             return true;
    //     }
        
    //     return parent::isFiltercomponent($component);
    // }

    public function getLabelText(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMINPUT_LOCATIONPOSTCATEGORIES:
            case self::COMPONENT_FILTERINPUT_LOCATIONPOSTCATEGORIES:
                return TranslationAPIFacade::getInstance()->__('Categories', 'pop-locationposts-processors');
        }
        
        return parent::getLabelText($component, $props);
    }

    public function getInputClass(array $component): string
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMINPUT_LOCATIONPOSTCATEGORIES:
            case self::COMPONENT_FILTERINPUT_LOCATIONPOSTCATEGORIES:
                return GD_FormInput_LocationPostCategories::class;
        }
        
        return parent::getInputClass($component);
    }

    public function getDbobjectField(array $component): ?string
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMINPUT_LOCATIONPOSTCATEGORIES:
                return 'locationpostcategories';
        }
        
        return parent::getDbobjectField($component);
    }

    public function getName(array $component): string
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMINPUT_LOCATIONPOSTCATEGORIES:
                return 'categories';
        }
        
        return parent::getName($component);
    }
}



