<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_URE_Module_Processor_FormGroups extends PoP_Module_Processor_FormComponentGroupsBase
{
    public const MODULE_URE_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_COMMUNITIES = 'ure-formcomponentgroup-selectabletypeahead-ure-communities';
    public const MODULE_URE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_PROFILES = 'filtercomponentgroup-selectabletypeahead-users';
    public const MODULE_URE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_COMMUNITYPLUSMEMBERS = 'filtercomponentgroup-selectabletypeahead-communityplusmembers';
    public const MODULE_URE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_COMMUNITIES_POST = 'filtercomponentgroup-selectabletypeahead-communities-post';
    public const MODULE_URE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_COMMUNITIES_USER = 'filtercomponentgroup-selectabletypeahead-communities-user';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_URE_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_COMMUNITIES],
            // [self::class, self::MODULE_URE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_PROFILES],
            [self::class, self::MODULE_URE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_COMMUNITYPLUSMEMBERS],
            [self::class, self::MODULE_URE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_COMMUNITIES_POST],
            [self::class, self::MODULE_URE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_COMMUNITIES_USER],
        );
    }

    public function getLabelClass(array $module)
    {
        $ret = parent::getLabelClass($module);

        switch ($module[1]) {
            // case self::MODULE_URE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_PROFILES:
            case self::MODULE_URE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_COMMUNITYPLUSMEMBERS:
            case self::MODULE_URE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_COMMUNITIES_POST:
            case self::MODULE_URE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_COMMUNITIES_USER:
                $ret .= ' col-sm-2';
                break;
        }

        return $ret;
    }
    public function getFormcontrolClass(array $module)
    {
        $ret = parent::getFormcontrolClass($module);

        switch ($module[1]) {
            // case self::MODULE_URE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_PROFILES:
            case self::MODULE_URE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_COMMUNITYPLUSMEMBERS:
            case self::MODULE_URE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_COMMUNITIES_POST:
            case self::MODULE_URE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_COMMUNITIES_USER:
                $ret .= ' col-sm-10';
                break;
        }

        return $ret;
    }

    public function getComponentSubmodule(array $module)
    {
        $components = array(
            self::MODULE_URE_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_COMMUNITIES => [GD_URE_Module_Processor_UserSelectableTypeaheadFormInputs::class, GD_URE_Module_Processor_UserSelectableTypeaheadFormInputs::MODULE_URE_FORMCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITIES],
            // self::MODULE_URE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_PROFILES => [self::class, self::MODULE_URE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_PROFILES],
            self::MODULE_URE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_COMMUNITYPLUSMEMBERS => [GD_URE_Module_Processor_UserSelectableTypeaheadFilterInputs::class, GD_URE_Module_Processor_UserSelectableTypeaheadFilterInputs::MODULE_URE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITYPLUSMEMBERS],
            self::MODULE_URE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_COMMUNITIES_POST => [GD_URE_Module_Processor_UserSelectableTypeaheadFilterInputs::class, GD_URE_Module_Processor_UserSelectableTypeaheadFilterInputs::MODULE_URE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITIES_POST],
            self::MODULE_URE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_COMMUNITIES_USER => [GD_URE_Module_Processor_UserSelectableTypeaheadFilterInputs::class, GD_URE_Module_Processor_UserSelectableTypeaheadFilterInputs::MODULE_URE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITIES_USER],
        );

        if ($component = $components[$module[1]] ?? null) {
            return $component;
        }

        return parent::getComponentSubmodule($module);
    }

    public function getInfo(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_URE_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_COMMUNITIES:
                return TranslationAPIFacade::getInstance()->__('Please select the communities you are a member of: all your content will also show under their profiles.', 'ure-popprocessors');
        }

        return parent::getInfo($module, $props);
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_URE_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_COMMUNITIES:
                $placeholder = TranslationAPIFacade::getInstance()->__('Community', 'pop-coreprocessors');
                $this->setProp([GD_UserCommunities_Module_Processor_TypeaheadTextFormInputs::class, GD_UserCommunities_Module_Processor_TypeaheadTextFormInputs::MODULE_FORMINPUT_TEXT_TYPEAHEADCOMMUNITIES], $props, 'placeholder', $placeholder);
                break;
        }

        parent::initModelProps($module, $props);
    }
}



