<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_RelatedPosts_Module_Processor_FormComponentGroups extends PoP_Module_Processor_FormComponentGroupsBase
{
    public final const COMPONENT_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_REFERENCES = 'formcomponentgroup-selectabletypeahead-references';
    public final const COMPONENT_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_REFERENCES = 'filtercomponentgroup-selectabletypeahead-references';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_REFERENCES],
            [self::class, self::COMPONENT_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_REFERENCES],
        );
    }

    public function getLabelClass(\PoP\ComponentModel\Component\Component $component)
    {
        $ret = parent::getLabelClass($component);

        switch ($component->name) {
            case self::COMPONENT_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_REFERENCES:
                $ret .= ' col-sm-2';
                break;
        }

        return $ret;
    }
    public function getFormcontrolClass(\PoP\ComponentModel\Component\Component $component)
    {
        $ret = parent::getFormcontrolClass($component);

        switch ($component->name) {
            case self::COMPONENT_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_REFERENCES:
                $ret .= ' col-sm-10';
                break;
        }

        return $ret;
    }

    public function getComponentSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        $components = array(
            self::COMPONENT_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_REFERENCES => [PoP_Module_Processor_PostSelectableTypeaheadFormComponents::class, PoP_Module_Processor_PostSelectableTypeaheadFormComponents::COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES],
            self::COMPONENT_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_REFERENCES => [PoP_Module_Processor_PostSelectableTypeaheadFilterComponents::class, PoP_Module_Processor_PostSelectableTypeaheadFilterComponents::COMPONENT_FILTERCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES],
        );

        if ($component = $components[$component->name] ?? null) {
            return $component;
        }

        return parent::getComponentSubcomponent($component);
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_REFERENCES:
                $placeholders = array(
                    self::COMPONENT_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_REFERENCES => TranslationAPIFacade::getInstance()->__('Type post title...', 'pop-coreprocessors'),
                );
                $placeholder = $placeholders[$component->name];
                $component = $this->getComponentSubcomponent($component);
                $this->setProp($component, $props, 'placeholder', $placeholder);
                break;
        }

        parent::initModelProps($component, $props);
    }

    public function getInfo(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_REFERENCES:
                return TranslationAPIFacade::getInstance()->__('Please select all related content, so the reader can easily access this inter-related information.', 'pop-coreprocessors');
        }

        return parent::getInfo($component, $props);
    }
}



