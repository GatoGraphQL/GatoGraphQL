<?php

class PoP_Module_Processor_UserSelectableTypeaheadAlertFormComponents extends PoP_Module_Processor_UserSelectableTypeaheadAlertFormComponentsBase
{
    public final const MODULE_FORMCOMPONENT_SELECTABLETYPEAHEADALERT_AUTHORS = 'formcomponent-selectabletypeaheadalert-authors';
    public final const MODULE_FORMCOMPONENT_SELECTABLETYPEAHEADALERT_COAUTHORS = 'formcomponent-selectabletypeaheadalert-coauthors';
    public final const MODULE_FILTERCOMPONENT_SELECTABLETYPEAHEADALERT_PROFILES = 'filtercomponent-selectabletypeaheadalert-selectableprofiles';
    public final const MODULE_FILTERCOMPONENT_SELECTABLETYPEAHEADALERT_COMMUNITYUSERS = 'filtercomponent-selectabletypeaheadalert-communityusers';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEADALERT_AUTHORS],
            [self::class, self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEADALERT_COAUTHORS],
            [self::class, self::MODULE_FILTERCOMPONENT_SELECTABLETYPEAHEADALERT_PROFILES],
            [self::class, self::MODULE_FILTERCOMPONENT_SELECTABLETYPEAHEADALERT_COMMUNITYUSERS],
        );
    }

    public function getSelectedModule(array $component)
    {
        switch ($component[1]) {
            case self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEADALERT_AUTHORS:
            case self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEADALERT_COAUTHORS:
                return [PoP_Module_Processor_UserCardLayouts::class, PoP_Module_Processor_UserCardLayouts::MODULE_LAYOUTUSER_CARD];
                
            case self::MODULE_FILTERCOMPONENT_SELECTABLETYPEAHEADALERT_PROFILES:
            case self::MODULE_FILTERCOMPONENT_SELECTABLETYPEAHEADALERT_COMMUNITYUSERS:
                return [PoP_Module_Processor_UserCardLayouts::class, PoP_Module_Processor_UserCardLayouts::MODULE_LAYOUTUSER_FILTERCARD];
        }

        return parent::getSelectedModule($component);
    }
    
    public function getHiddeninputModule(array $component)
    {
        switch ($component[1]) {
            case self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEADALERT_AUTHORS:
                return [GD_Processor_SelectableHiddenInputFormInputs::class, GD_Processor_SelectableHiddenInputFormInputs::MODULE_FORMINPUT_HIDDENINPUT_SELECTABLELAYOUTAUTHORS];

            case self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEADALERT_COAUTHORS:
                return [GD_Processor_SelectableHiddenInputFormInputs::class, GD_Processor_SelectableHiddenInputFormInputs::MODULE_FORMINPUT_HIDDENINPUT_SELECTABLELAYOUTCOAUTHORS];
            
            case self::MODULE_FILTERCOMPONENT_SELECTABLETYPEAHEADALERT_PROFILES:
                return [GD_Processor_SelectableHiddenInputFormInputs::class, GD_Processor_SelectableHiddenInputFormInputs::MODULE_FILTERINPUT_HIDDENINPUT_SELECTABLELAYOUTPROFILES];

            case self::MODULE_FILTERCOMPONENT_SELECTABLETYPEAHEADALERT_COMMUNITYUSERS:
                return [GD_Processor_SelectableHiddenInputFormInputs::class, GD_Processor_SelectableHiddenInputFormInputs::MODULE_FILTERINPUT_HIDDENINPUT_SELECTABLELAYOUTCOMMUNITYUSERS];
        }

        return parent::getHiddeninputModule($component);
    }

    public function isMultiple(array $component): bool
    {
        switch ($component[1]) {
            case self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEADALERT_AUTHORS:
            case self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEADALERT_COAUTHORS:
            case self::MODULE_FILTERCOMPONENT_SELECTABLETYPEAHEADALERT_PROFILES:
            case self::MODULE_FILTERCOMPONENT_SELECTABLETYPEAHEADALERT_COMMUNITYUSERS:
                return true;
        }

        return parent::isMultiple($component);
    }
}



