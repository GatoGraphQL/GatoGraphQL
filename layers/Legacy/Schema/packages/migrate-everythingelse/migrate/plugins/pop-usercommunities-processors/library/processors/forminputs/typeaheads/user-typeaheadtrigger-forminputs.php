<?php

class GD_URE_Module_Processor_UserSelectableTypeaheadAlertFormComponents extends PoP_Module_Processor_UserSelectableTypeaheadAlertFormComponentsBase
{
    public final const COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEADALERT_USERCOMMUNITIES = 'formcomponent-selectabletypeaheadalert-usercommunities';
    public final const COMPONENT_FILTERCOMPONENT_SELECTABLETYPEAHEADALERT_COMMUNITIES = 'filtercomponent-selectabletypeaheadalert-communities';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEADALERT_USERCOMMUNITIES],
            [self::class, self::COMPONENT_FILTERCOMPONENT_SELECTABLETYPEAHEADALERT_COMMUNITIES],
        );
    }
    
    public function getHiddeninputModule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEADALERT_USERCOMMUNITIES:
                return [GD_URE_Processor_SelectableHiddenInputFormInputs::class, GD_URE_Processor_SelectableHiddenInputFormInputs::COMPONENT_FORMINPUT_HIDDENINPUT_SELECTABLELAYOUTUSERCOMMUNITIES];

            case self::COMPONENT_FILTERCOMPONENT_SELECTABLETYPEAHEADALERT_COMMUNITIES:
                return [GD_URE_Processor_SelectableHiddenInputFormInputs::class, GD_URE_Processor_SelectableHiddenInputFormInputs::COMPONENT_FILTERINPUT_HIDDENINPUT_SELECTABLELAYOUTCOMMUNITIES];
        }

        return parent::getHiddeninputModule($component);
    }

    public function isMultiple(array $component): bool
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEADALERT_USERCOMMUNITIES:
            case self::COMPONENT_FILTERCOMPONENT_SELECTABLETYPEAHEADALERT_COMMUNITIES:
                return true;
        }

        return parent::isMultiple($component);
    }

    public function getAlertClass(array $component, array &$props)
    {
        $ret = parent::getAlertClass($component, $props);

        switch ($component[1]) {
            case self::COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEADALERT_USERCOMMUNITIES:
                $ret .= ' alert-narrow';
                break;
        }

        return $ret;
    }
}



