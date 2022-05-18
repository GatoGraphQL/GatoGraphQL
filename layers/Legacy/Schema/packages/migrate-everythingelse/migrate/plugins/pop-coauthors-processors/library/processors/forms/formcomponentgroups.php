<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_CAP_Module_Processor_FormComponentGroups extends PoP_Module_Processor_FormComponentGroupsBase
{
    public final const MODULE_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_POSTAUTHORS = 'formcomponentgroup-selectabletypeahead-postauthors';
    public final const MODULE_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_POSTCOAUTHORS = 'formcomponentgroup-selectabletypeahead-postcoauthors';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_POSTAUTHORS],
            [self::class, self::MODULE_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_POSTCOAUTHORS],
        );
    }

    public function getComponentSubmodule(array $componentVariation)
    {
        $components = array(
            self::MODULE_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_POSTAUTHORS => [GD_CAP_Module_Processor_UserSelectableTypeaheadFormInputs::class, GD_CAP_Module_Processor_UserSelectableTypeaheadFormInputs::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEAD_POSTAUTHORS],
            self::MODULE_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_POSTCOAUTHORS => [GD_CAP_Module_Processor_UserSelectableTypeaheadFormInputs::class, GD_CAP_Module_Processor_UserSelectableTypeaheadFormInputs::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEAD_POSTCOAUTHORS],
        );

        if ($component = $components[$componentVariation[1]] ?? null) {
            return $component;
        }

        return parent::getComponentSubmodule($componentVariation);
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_POSTAUTHORS:
            case self::MODULE_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_POSTCOAUTHORS:
                $placeholders = array(
                    self::MODULE_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_POSTAUTHORS => TranslationAPIFacade::getInstance()->__('Type name...', 'pop-coreprocessors'),
                    self::MODULE_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_POSTCOAUTHORS => TranslationAPIFacade::getInstance()->__('Type name...', 'pop-coreprocessors'),
                );
                $placeholder = $placeholders[$componentVariation[1]];
                $component = $this->getComponentSubmodule($componentVariation);
                $this->setProp($component, $props, 'placeholder', $placeholder);
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }

    public function getInfo(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_POSTAUTHORS:
            case self::MODULE_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_POSTCOAUTHORS:
                return TranslationAPIFacade::getInstance()->__('Co-authoring this post with other users? Select them all here, they will not only appear as co-owners in the webpage, but will also be able to edit this post.', 'pop-coreprocessors');
        }

        return parent::getInfo($componentVariation, $props);
    }

    public function getLabel(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_POSTCOAUTHORS:
                return TranslationAPIFacade::getInstance()->__('Co-authors', 'pop-coreprocessors');
        }

        return parent::getLabel($componentVariation, $props);
    }
}



