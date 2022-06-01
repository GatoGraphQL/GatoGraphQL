<?php

class PoP_Module_Processor_HighlightReferencedbyLayouts extends PoP_Module_Processor_SubcomponentLayoutsBase
{
    public final const COMPONENT_SUBCOMPONENT_HIGHLIGHTS = 'subcomponent-highlights';
    public final const COMPONENT_LAZYSUBCOMPONENT_HIGHLIGHTS = 'lazysubcomponent-highlights';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_SUBCOMPONENT_HIGHLIGHTS,
            self::COMPONENT_LAZYSUBCOMPONENT_HIGHLIGHTS,
        );
    }

    public function getSubcomponentField(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_SUBCOMPONENT_HIGHLIGHTS:
                return 'highlights';

            case self::COMPONENT_LAZYSUBCOMPONENT_HIGHLIGHTS:
                return 'highlightsLazy';
        }
    }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getLayoutSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_SUBCOMPONENT_HIGHLIGHTS:
                $ret[] = [Wassup_Module_Processor_LayoutContents::class, Wassup_Module_Processor_LayoutContents::COMPONENT_CONTENTLAYOUT_HIGHLIGHTS];
                break;

            case self::COMPONENT_LAZYSUBCOMPONENT_HIGHLIGHTS:
                $ret[] = [Wassup_Module_Processor_LayoutContents::class, Wassup_Module_Processor_LayoutContents::COMPONENT_CONTENTLAYOUT_HIGHLIGHTS_APPENDABLE];
                break;
        }

        return $ret;
    }

    public function isIndividual(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_SUBCOMPONENT_HIGHLIGHTS:
            case self::COMPONENT_LAZYSUBCOMPONENT_HIGHLIGHTS:
                return false;
        }

        return parent::isIndividual($component, $props);
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_SUBCOMPONENT_HIGHLIGHTS:
            case self::COMPONENT_LAZYSUBCOMPONENT_HIGHLIGHTS:
                $this->appendProp($component, $props, 'class', 'referencedby clearfix');
                break;
        }

        parent::initModelProps($component, $props);
    }
}



