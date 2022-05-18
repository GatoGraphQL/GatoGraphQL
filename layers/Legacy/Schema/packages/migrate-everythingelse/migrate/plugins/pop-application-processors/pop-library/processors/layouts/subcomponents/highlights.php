<?php

class PoP_Module_Processor_HighlightReferencedbyLayouts extends PoP_Module_Processor_SubcomponentLayoutsBase
{
    public final const MODULE_SUBCOMPONENT_HIGHLIGHTS = 'subcomponent-highlights';
    public final const MODULE_LAZYSUBCOMPONENT_HIGHLIGHTS = 'lazysubcomponent-highlights';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_SUBCOMPONENT_HIGHLIGHTS],
            [self::class, self::COMPONENT_LAZYSUBCOMPONENT_HIGHLIGHTS],
        );
    }

    public function getSubcomponentField(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_SUBCOMPONENT_HIGHLIGHTS:
                return 'highlights';

            case self::COMPONENT_LAZYSUBCOMPONENT_HIGHLIGHTS:
                return 'highlightsLazy';
        }
    }

    public function getLayoutSubmodules(array $component)
    {
        $ret = parent::getLayoutSubmodules($component);

        switch ($component[1]) {
            case self::COMPONENT_SUBCOMPONENT_HIGHLIGHTS:
                $ret[] = [Wassup_Module_Processor_LayoutContents::class, Wassup_Module_Processor_LayoutContents::COMPONENT_CONTENTLAYOUT_HIGHLIGHTS];
                break;

            case self::COMPONENT_LAZYSUBCOMPONENT_HIGHLIGHTS:
                $ret[] = [Wassup_Module_Processor_LayoutContents::class, Wassup_Module_Processor_LayoutContents::COMPONENT_CONTENTLAYOUT_HIGHLIGHTS_APPENDABLE];
                break;
        }

        return $ret;
    }

    public function isIndividual(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_SUBCOMPONENT_HIGHLIGHTS:
            case self::COMPONENT_LAZYSUBCOMPONENT_HIGHLIGHTS:
                return false;
        }

        return parent::isIndividual($component, $props);
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_SUBCOMPONENT_HIGHLIGHTS:
            case self::COMPONENT_LAZYSUBCOMPONENT_HIGHLIGHTS:
                $this->appendProp($component, $props, 'class', 'referencedby clearfix');
                break;
        }

        parent::initModelProps($component, $props);
    }
}



