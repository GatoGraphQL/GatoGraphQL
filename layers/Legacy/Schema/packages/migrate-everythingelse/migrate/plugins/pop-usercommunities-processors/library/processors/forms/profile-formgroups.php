<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_URE_Module_Processor_FormGroups extends PoP_Module_Processor_FormComponentGroupsBase
{
    public final const COMPONENT_URE_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_COMMUNITIES = 'ure-formcomponentgroup-selectabletypeahead-ure-communities';
    public final const COMPONENT_URE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_PROFILES = 'filtercomponentgroup-selectabletypeahead-users';
    public final const COMPONENT_URE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_COMMUNITYPLUSMEMBERS = 'filtercomponentgroup-selectabletypeahead-communityplusmembers';
    public final const COMPONENT_URE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_COMMUNITIES_POST = 'filtercomponentgroup-selectabletypeahead-communities-post';
    public final const COMPONENT_URE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_COMMUNITIES_USER = 'filtercomponentgroup-selectabletypeahead-communities-user';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_URE_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_COMMUNITIES],
            // [self::class, self::COMPONENT_URE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_PROFILES],
            [self::class, self::COMPONENT_URE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_COMMUNITYPLUSMEMBERS],
            [self::class, self::COMPONENT_URE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_COMMUNITIES_POST],
            [self::class, self::COMPONENT_URE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_COMMUNITIES_USER],
        );
    }

    public function getLabelClass(array $component)
    {
        $ret = parent::getLabelClass($component);

        switch ($component[1]) {
            // case self::COMPONENT_URE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_PROFILES:
            case self::COMPONENT_URE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_COMMUNITYPLUSMEMBERS:
            case self::COMPONENT_URE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_COMMUNITIES_POST:
            case self::COMPONENT_URE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_COMMUNITIES_USER:
                $ret .= ' col-sm-2';
                break;
        }

        return $ret;
    }
    public function getFormcontrolClass(array $component)
    {
        $ret = parent::getFormcontrolClass($component);

        switch ($component[1]) {
            // case self::COMPONENT_URE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_PROFILES:
            case self::COMPONENT_URE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_COMMUNITYPLUSMEMBERS:
            case self::COMPONENT_URE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_COMMUNITIES_POST:
            case self::COMPONENT_URE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_COMMUNITIES_USER:
                $ret .= ' col-sm-10';
                break;
        }

        return $ret;
    }

    public function getComponentSubmodule(array $component)
    {
        $components = array(
            self::COMPONENT_URE_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_COMMUNITIES => [GD_URE_Module_Processor_UserSelectableTypeaheadFormInputs::class, GD_URE_Module_Processor_UserSelectableTypeaheadFormInputs::COMPONENT_URE_FORMCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITIES],
            // self::COMPONENT_URE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_PROFILES => [self::class, self::COMPONENT_URE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_PROFILES],
            self::COMPONENT_URE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_COMMUNITYPLUSMEMBERS => [GD_URE_Module_Processor_UserSelectableTypeaheadFilterInputs::class, GD_URE_Module_Processor_UserSelectableTypeaheadFilterInputs::COMPONENT_URE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITYPLUSMEMBERS],
            self::COMPONENT_URE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_COMMUNITIES_POST => [GD_URE_Module_Processor_UserSelectableTypeaheadFilterInputs::class, GD_URE_Module_Processor_UserSelectableTypeaheadFilterInputs::COMPONENT_URE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITIES_POST],
            self::COMPONENT_URE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_COMMUNITIES_USER => [GD_URE_Module_Processor_UserSelectableTypeaheadFilterInputs::class, GD_URE_Module_Processor_UserSelectableTypeaheadFilterInputs::COMPONENT_URE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITIES_USER],
        );

        if ($component = $components[$component[1]] ?? null) {
            return $component;
        }

        return parent::getComponentSubmodule($component);
    }

    public function getInfo(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_URE_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_COMMUNITIES:
                return TranslationAPIFacade::getInstance()->__('Please select the communities you are a member of: all your content will also show under their profiles.', 'ure-popprocessors');
        }

        return parent::getInfo($component, $props);
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_URE_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_COMMUNITIES:
                $placeholder = TranslationAPIFacade::getInstance()->__('Community', 'pop-coreprocessors');
                $this->setProp([GD_UserCommunities_Module_Processor_TypeaheadTextFormInputs::class, GD_UserCommunities_Module_Processor_TypeaheadTextFormInputs::COMPONENT_FORMINPUT_TEXT_TYPEAHEADCOMMUNITIES], $props, 'placeholder', $placeholder);
                break;
        }

        parent::initModelProps($component, $props);
    }
}



