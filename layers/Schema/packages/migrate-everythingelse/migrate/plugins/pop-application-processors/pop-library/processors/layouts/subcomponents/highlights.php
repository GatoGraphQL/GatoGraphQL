<?php
use PoPSchema\Highlights\TypeResolvers\HighlightTypeResolver;

class PoP_Module_Processor_HighlightReferencedbyLayouts extends PoP_Module_Processor_SubcomponentLayoutsBase
{
    public const MODULE_SUBCOMPONENT_HIGHLIGHTS = 'subcomponent-highlights';
    public const MODULE_LAZYSUBCOMPONENT_HIGHLIGHTS = 'lazysubcomponent-highlights';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_SUBCOMPONENT_HIGHLIGHTS],
            [self::class, self::MODULE_LAZYSUBCOMPONENT_HIGHLIGHTS],
        );
    }

    public function getSubcomponentField(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_SUBCOMPONENT_HIGHLIGHTS:
                return 'highlights';

            case self::MODULE_LAZYSUBCOMPONENT_HIGHLIGHTS:
                return 'highlightsLazy';
        }
    }

    public function getLayoutSubmodules(array $module)
    {
        $ret = parent::getLayoutSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_SUBCOMPONENT_HIGHLIGHTS:
                $ret[] = [Wassup_Module_Processor_LayoutContents::class, Wassup_Module_Processor_LayoutContents::MODULE_CONTENTLAYOUT_HIGHLIGHTS];
                break;

            case self::MODULE_LAZYSUBCOMPONENT_HIGHLIGHTS:
                $ret[] = [Wassup_Module_Processor_LayoutContents::class, Wassup_Module_Processor_LayoutContents::MODULE_CONTENTLAYOUT_HIGHLIGHTS_APPENDABLE];
                break;
        }

        return $ret;
    }

    public function isIndividual(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_SUBCOMPONENT_HIGHLIGHTS:
            case self::MODULE_LAZYSUBCOMPONENT_HIGHLIGHTS:
                return false;
        }

        return parent::isIndividual($module, $props);
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_SUBCOMPONENT_HIGHLIGHTS:
            case self::MODULE_LAZYSUBCOMPONENT_HIGHLIGHTS:
                $this->appendProp($module, $props, 'class', 'referencedby clearfix');
                break;
        }

        parent::initModelProps($module, $props);
    }
}



