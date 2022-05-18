<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_CAP_Module_Processor_FormComponentGroups extends PoP_Module_Processor_FormComponentGroupsBase
{
    public final const COMPONENT_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_POSTAUTHORS = 'formcomponentgroup-selectabletypeahead-postauthors';
    public final const COMPONENT_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_POSTCOAUTHORS = 'formcomponentgroup-selectabletypeahead-postcoauthors';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_POSTAUTHORS],
            [self::class, self::COMPONENT_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_POSTCOAUTHORS],
        );
    }

    public function getComponentSubmodule(array $component)
    {
        $components = array(
            self::COMPONENT_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_POSTAUTHORS => [GD_CAP_Module_Processor_UserSelectableTypeaheadFormInputs::class, GD_CAP_Module_Processor_UserSelectableTypeaheadFormInputs::COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEAD_POSTAUTHORS],
            self::COMPONENT_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_POSTCOAUTHORS => [GD_CAP_Module_Processor_UserSelectableTypeaheadFormInputs::class, GD_CAP_Module_Processor_UserSelectableTypeaheadFormInputs::COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEAD_POSTCOAUTHORS],
        );

        if ($component = $components[$component[1]] ?? null) {
            return $component;
        }

        return parent::getComponentSubmodule($component);
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_POSTAUTHORS:
            case self::COMPONENT_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_POSTCOAUTHORS:
                $placeholders = array(
                    self::COMPONENT_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_POSTAUTHORS => TranslationAPIFacade::getInstance()->__('Type name...', 'pop-coreprocessors'),
                    self::COMPONENT_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_POSTCOAUTHORS => TranslationAPIFacade::getInstance()->__('Type name...', 'pop-coreprocessors'),
                );
                $placeholder = $placeholders[$component[1]];
                $component = $this->getComponentSubmodule($component);
                $this->setProp($component, $props, 'placeholder', $placeholder);
                break;
        }

        parent::initModelProps($component, $props);
    }

    public function getInfo(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_POSTAUTHORS:
            case self::COMPONENT_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_POSTCOAUTHORS:
                return TranslationAPIFacade::getInstance()->__('Co-authoring this post with other users? Select them all here, they will not only appear as co-owners in the webpage, but will also be able to edit this post.', 'pop-coreprocessors');
        }

        return parent::getInfo($component, $props);
    }

    public function getLabel(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_POSTCOAUTHORS:
                return TranslationAPIFacade::getInstance()->__('Co-authors', 'pop-coreprocessors');
        }

        return parent::getLabel($component, $props);
    }
}



