<?php

class PoP_Module_Processor_HighlightReferencedbyLayouts extends PoP_Module_Processor_SubcomponentLayoutsBase
{
    public final const MODULE_SUBCOMPONENT_HIGHLIGHTS = 'subcomponent-highlights';
    public final const MODULE_LAZYSUBCOMPONENT_HIGHLIGHTS = 'lazysubcomponent-highlights';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_SUBCOMPONENT_HIGHLIGHTS],
            [self::class, self::MODULE_LAZYSUBCOMPONENT_HIGHLIGHTS],
        );
    }

    public function getSubcomponentField(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_SUBCOMPONENT_HIGHLIGHTS:
                return 'highlights';

            case self::MODULE_LAZYSUBCOMPONENT_HIGHLIGHTS:
                return 'highlightsLazy';
        }
    }

    public function getLayoutSubmodules(array $componentVariation)
    {
        $ret = parent::getLayoutSubmodules($componentVariation);

        switch ($componentVariation[1]) {
            case self::MODULE_SUBCOMPONENT_HIGHLIGHTS:
                $ret[] = [Wassup_Module_Processor_LayoutContents::class, Wassup_Module_Processor_LayoutContents::MODULE_CONTENTLAYOUT_HIGHLIGHTS];
                break;

            case self::MODULE_LAZYSUBCOMPONENT_HIGHLIGHTS:
                $ret[] = [Wassup_Module_Processor_LayoutContents::class, Wassup_Module_Processor_LayoutContents::MODULE_CONTENTLAYOUT_HIGHLIGHTS_APPENDABLE];
                break;
        }

        return $ret;
    }

    public function isIndividual(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_SUBCOMPONENT_HIGHLIGHTS:
            case self::MODULE_LAZYSUBCOMPONENT_HIGHLIGHTS:
                return false;
        }

        return parent::isIndividual($componentVariation, $props);
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_SUBCOMPONENT_HIGHLIGHTS:
            case self::MODULE_LAZYSUBCOMPONENT_HIGHLIGHTS:
                $this->appendProp($componentVariation, $props, 'class', 'referencedby clearfix');
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}



