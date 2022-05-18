<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_RelatedPosts_Module_Processor_FormComponentGroups extends PoP_Module_Processor_FormComponentGroupsBase
{
    public final const MODULE_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_REFERENCES = 'formcomponentgroup-selectabletypeahead-references';
    public final const MODULE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_REFERENCES = 'filtercomponentgroup-selectabletypeahead-references';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_REFERENCES],
            [self::class, self::MODULE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_REFERENCES],
        );
    }

    public function getLabelClass(array $module)
    {
        $ret = parent::getLabelClass($module);

        switch ($module[1]) {
            case self::MODULE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_REFERENCES:
                $ret .= ' col-sm-2';
                break;
        }

        return $ret;
    }
    public function getFormcontrolClass(array $module)
    {
        $ret = parent::getFormcontrolClass($module);

        switch ($module[1]) {
            case self::MODULE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_REFERENCES:
                $ret .= ' col-sm-10';
                break;
        }

        return $ret;
    }

    public function getComponentSubmodule(array $module)
    {
        $components = array(
            self::MODULE_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_REFERENCES => [PoP_Module_Processor_PostSelectableTypeaheadFormComponents::class, PoP_Module_Processor_PostSelectableTypeaheadFormComponents::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES],
            self::MODULE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_REFERENCES => [PoP_Module_Processor_PostSelectableTypeaheadFilterComponents::class, PoP_Module_Processor_PostSelectableTypeaheadFilterComponents::MODULE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES],
        );

        if ($component = $components[$module[1]] ?? null) {
            return $component;
        }

        return parent::getComponentSubmodule($module);
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_REFERENCES:
                $placeholders = array(
                    self::MODULE_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_REFERENCES => TranslationAPIFacade::getInstance()->__('Type post title...', 'pop-coreprocessors'),
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
            case self::MODULE_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_REFERENCES:
                return TranslationAPIFacade::getInstance()->__('Please select all related content, so the reader can easily access this inter-related information.', 'pop-coreprocessors');
        }

        return parent::getInfo($module, $props);
    }
}



