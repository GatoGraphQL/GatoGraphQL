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

    public function getLabelClass(array $componentVariation)
    {
        $ret = parent::getLabelClass($componentVariation);

        switch ($componentVariation[1]) {
            case self::MODULE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_REFERENCES:
                $ret .= ' col-sm-2';
                break;
        }

        return $ret;
    }
    public function getFormcontrolClass(array $componentVariation)
    {
        $ret = parent::getFormcontrolClass($componentVariation);

        switch ($componentVariation[1]) {
            case self::MODULE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_REFERENCES:
                $ret .= ' col-sm-10';
                break;
        }

        return $ret;
    }

    public function getComponentSubmodule(array $componentVariation)
    {
        $components = array(
            self::MODULE_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_REFERENCES => [PoP_Module_Processor_PostSelectableTypeaheadFormComponents::class, PoP_Module_Processor_PostSelectableTypeaheadFormComponents::MODULE_FORMCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES],
            self::MODULE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_REFERENCES => [PoP_Module_Processor_PostSelectableTypeaheadFilterComponents::class, PoP_Module_Processor_PostSelectableTypeaheadFilterComponents::MODULE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES],
        );

        if ($component = $components[$componentVariation[1]] ?? null) {
            return $component;
        }

        return parent::getComponentSubmodule($componentVariation);
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_REFERENCES:
                $placeholders = array(
                    self::MODULE_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_REFERENCES => TranslationAPIFacade::getInstance()->__('Type post title...', 'pop-coreprocessors'),
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
            case self::MODULE_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_REFERENCES:
                return TranslationAPIFacade::getInstance()->__('Please select all related content, so the reader can easily access this inter-related information.', 'pop-coreprocessors');
        }

        return parent::getInfo($componentVariation, $props);
    }
}



