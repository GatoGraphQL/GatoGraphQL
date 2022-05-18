<?php

class GD_URE_Module_Processor_UserSelectableTypeaheadAlertFormComponents extends PoP_Module_Processor_UserSelectableTypeaheadAlertFormComponentsBase
{
    public final const MODULE_FORMCOMPONENT_SELECTABLETYPEAHEADALERT_USERCOMMUNITIES = 'formcomponent-selectabletypeaheadalert-usercommunities';
    public final const MODULE_FILTERCOMPONENT_SELECTABLETYPEAHEADALERT_COMMUNITIES = 'filtercomponent-selectabletypeaheadalert-communities';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEADALERT_USERCOMMUNITIES],
            [self::class, self::MODULE_FILTERCOMPONENT_SELECTABLETYPEAHEADALERT_COMMUNITIES],
        );
    }
    
    public function getHiddeninputModule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEADALERT_USERCOMMUNITIES:
                return [GD_URE_Processor_SelectableHiddenInputFormInputs::class, GD_URE_Processor_SelectableHiddenInputFormInputs::MODULE_FORMINPUT_HIDDENINPUT_SELECTABLELAYOUTUSERCOMMUNITIES];

            case self::MODULE_FILTERCOMPONENT_SELECTABLETYPEAHEADALERT_COMMUNITIES:
                return [GD_URE_Processor_SelectableHiddenInputFormInputs::class, GD_URE_Processor_SelectableHiddenInputFormInputs::MODULE_FILTERINPUT_HIDDENINPUT_SELECTABLELAYOUTCOMMUNITIES];
        }

        return parent::getHiddeninputModule($module);
    }

    public function isMultiple(array $module): bool
    {
        switch ($module[1]) {
            case self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEADALERT_USERCOMMUNITIES:
            case self::MODULE_FILTERCOMPONENT_SELECTABLETYPEAHEADALERT_COMMUNITIES:
                return true;
        }

        return parent::isMultiple($module);
    }

    public function getAlertClass(array $module, array &$props)
    {
        $ret = parent::getAlertClass($module, $props);

        switch ($module[1]) {
            case self::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEADALERT_USERCOMMUNITIES:
                $ret .= ' alert-narrow';
                break;
        }

        return $ret;
    }
}



