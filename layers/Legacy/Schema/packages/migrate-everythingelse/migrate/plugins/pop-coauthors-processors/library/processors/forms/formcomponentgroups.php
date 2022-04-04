<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_CAP_Module_Processor_FormComponentGroups extends PoP_Module_Processor_FormComponentGroupsBase
{
    public final const MODULE_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_POSTAUTHORS = 'formcomponentgroup-selectabletypeahead-postauthors';
    public final const MODULE_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_POSTCOAUTHORS = 'formcomponentgroup-selectabletypeahead-postcoauthors';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_POSTAUTHORS],
            [self::class, self::MODULE_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_POSTCOAUTHORS],
        );
    }

    public function getComponentSubmodule(array $module)
    {
        $components = array(
            self::MODULE_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_POSTAUTHORS => [GD_CAP_Module_Processor_UserSelectableTypeaheadFormInputs::class, GD_CAP_Module_Processor_UserSelectableTypeaheadFormInputs::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEAD_POSTAUTHORS],
            self::MODULE_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_POSTCOAUTHORS => [GD_CAP_Module_Processor_UserSelectableTypeaheadFormInputs::class, GD_CAP_Module_Processor_UserSelectableTypeaheadFormInputs::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEAD_POSTCOAUTHORS],
        );

        if ($component = $components[$module[1]] ?? null) {
            return $component;
        }

        return parent::getComponentSubmodule($module);
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_POSTAUTHORS:
            case self::MODULE_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_POSTCOAUTHORS:
                $placeholders = array(
                    self::MODULE_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_POSTAUTHORS => TranslationAPIFacade::getInstance()->__('Type name...', 'pop-coreprocessors'),
                    self::MODULE_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_POSTCOAUTHORS => TranslationAPIFacade::getInstance()->__('Type name...', 'pop-coreprocessors'),
                );
                $placeholder = $placeholders[$module[1]];
                $component = $this->getComponentSubmodule($module);
                $this->setProp($component, $props, 'placeholder', $placeholder);
                break;
        }

        parent::initModelProps($module, $props);
    }

    public function getInfo(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_POSTAUTHORS:
            case self::MODULE_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_POSTCOAUTHORS:
                return TranslationAPIFacade::getInstance()->__('Co-authoring this post with other users? Select them all here, they will not only appear as co-owners in the webpage, but will also be able to edit this post.', 'pop-coreprocessors');
        }

        return parent::getInfo($module, $props);
    }

    public function getLabel(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_POSTCOAUTHORS:
                return TranslationAPIFacade::getInstance()->__('Co-authors', 'pop-coreprocessors');
        }

        return parent::getLabel($module, $props);
    }
}



