<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_Custom_EM_Module_Processor_MultiSelectFormInputs extends PoP_Module_Processor_MultiSelectFormInputsBase
{
    public final const COMPONENT_FORMINPUT_LOCATIONPOSTCATEGORIES = 'forminput-locationpostcategories';
    public final const COMPONENT_FILTERINPUT_LOCATIONPOSTCATEGORIES = 'filterinput-locationpostcategories';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FORMINPUT_LOCATIONPOSTCATEGORIES,
            self::COMPONENT_FILTERINPUT_LOCATIONPOSTCATEGORIES,
        );
    }

    // public function isFiltercomponent(\PoP\ComponentModel\Component\Component $component)
    // {
    //     switch ($component->name) {
    //         case self::COMPONENT_FILTERINPUT_LOCATIONPOSTCATEGORIES:
    //             return true;
    //     }
        
    //     return parent::isFiltercomponent($component);
    // }

    public function getLabelText(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_FORMINPUT_LOCATIONPOSTCATEGORIES:
            case self::COMPONENT_FILTERINPUT_LOCATIONPOSTCATEGORIES:
                return TranslationAPIFacade::getInstance()->__('Categories', 'pop-locationposts-processors');
        }
        
        return parent::getLabelText($component, $props);
    }

    public function getInputClass(\PoP\ComponentModel\Component\Component $component): string
    {
        switch ($component->name) {
            case self::COMPONENT_FORMINPUT_LOCATIONPOSTCATEGORIES:
            case self::COMPONENT_FILTERINPUT_LOCATIONPOSTCATEGORIES:
                return GD_FormInput_LocationPostCategories::class;
        }
        
        return parent::getInputClass($component);
    }

    public function getDbobjectField(\PoP\ComponentModel\Component\Component $component): ?string
    {
        switch ($component->name) {
            case self::COMPONENT_FORMINPUT_LOCATIONPOSTCATEGORIES:
                return 'locationpostcategories';
        }
        
        return parent::getDbobjectField($component);
    }

    public function getName(\PoP\ComponentModel\Component\Component $component): string
    {
        switch ($component->name) {
            case self::COMPONENT_FORMINPUT_LOCATIONPOSTCATEGORIES:
                return 'categories';
        }
        
        return parent::getName($component);
    }
}



