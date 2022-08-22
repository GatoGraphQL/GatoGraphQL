<?php

class PoP_Module_Processor_UserSelectableTypeaheadAlertFormComponents extends PoP_Module_Processor_UserSelectableTypeaheadAlertFormComponentsBase
{
    public final const COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEADALERT_AUTHORS = 'formcomponent-selectabletypeaheadalert-authors';
    public final const COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEADALERT_COAUTHORS = 'formcomponent-selectabletypeaheadalert-coauthors';
    public final const COMPONENT_FILTERCOMPONENT_SELECTABLETYPEAHEADALERT_PROFILES = 'filtercomponent-selectabletypeaheadalert-selectableprofiles';
    public final const COMPONENT_FILTERCOMPONENT_SELECTABLETYPEAHEADALERT_COMMUNITYUSERS = 'filtercomponent-selectabletypeaheadalert-communityusers';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEADALERT_AUTHORS,
            self::COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEADALERT_COAUTHORS,
            self::COMPONENT_FILTERCOMPONENT_SELECTABLETYPEAHEADALERT_PROFILES,
            self::COMPONENT_FILTERCOMPONENT_SELECTABLETYPEAHEADALERT_COMMUNITYUSERS,
        );
    }

    public function getSelectedComponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEADALERT_AUTHORS:
            case self::COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEADALERT_COAUTHORS:
                return [PoP_Module_Processor_UserCardLayouts::class, PoP_Module_Processor_UserCardLayouts::COMPONENT_LAYOUTUSER_CARD];
                
            case self::COMPONENT_FILTERCOMPONENT_SELECTABLETYPEAHEADALERT_PROFILES:
            case self::COMPONENT_FILTERCOMPONENT_SELECTABLETYPEAHEADALERT_COMMUNITYUSERS:
                return [PoP_Module_Processor_UserCardLayouts::class, PoP_Module_Processor_UserCardLayouts::COMPONENT_LAYOUTUSER_FILTERCARD];
        }

        return parent::getSelectedComponent($component);
    }
    
    public function getHiddenInputComponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEADALERT_AUTHORS:
                return [GD_Processor_SelectableHiddenInputFormInputs::class, GD_Processor_SelectableHiddenInputFormInputs::COMPONENT_FORMINPUT_HIDDENINPUT_SELECTABLELAYOUTAUTHORS];

            case self::COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEADALERT_COAUTHORS:
                return [GD_Processor_SelectableHiddenInputFormInputs::class, GD_Processor_SelectableHiddenInputFormInputs::COMPONENT_FORMINPUT_HIDDENINPUT_SELECTABLELAYOUTCOAUTHORS];
            
            case self::COMPONENT_FILTERCOMPONENT_SELECTABLETYPEAHEADALERT_PROFILES:
                return [GD_Processor_SelectableHiddenInputFormInputs::class, GD_Processor_SelectableHiddenInputFormInputs::COMPONENT_FILTERINPUT_HIDDENINPUT_SELECTABLELAYOUTPROFILES];

            case self::COMPONENT_FILTERCOMPONENT_SELECTABLETYPEAHEADALERT_COMMUNITYUSERS:
                return [GD_Processor_SelectableHiddenInputFormInputs::class, GD_Processor_SelectableHiddenInputFormInputs::COMPONENT_FILTERINPUT_HIDDENINPUT_SELECTABLELAYOUTCOMMUNITYUSERS];
        }

        return parent::getHiddenInputComponent($component);
    }

    public function isMultiple(\PoP\ComponentModel\Component\Component $component): bool
    {
        switch ($component->name) {
            case self::COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEADALERT_AUTHORS:
            case self::COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEADALERT_COAUTHORS:
            case self::COMPONENT_FILTERCOMPONENT_SELECTABLETYPEAHEADALERT_PROFILES:
            case self::COMPONENT_FILTERCOMPONENT_SELECTABLETYPEAHEADALERT_COMMUNITYUSERS:
                return true;
        }

        return parent::isMultiple($component);
    }
}



