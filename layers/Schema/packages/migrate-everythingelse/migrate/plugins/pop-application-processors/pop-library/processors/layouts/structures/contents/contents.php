<?php

class Wassup_Module_Processor_LayoutContents extends PoP_Module_Processor_ContentsBase
{
    public const MODULE_CONTENTLAYOUT_HIGHLIGHTS = 'contentlayout-highlights';
    public const MODULE_CONTENTLAYOUT_HIGHLIGHTS_APPENDABLE = 'contentlayout-highlights-appendable';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_CONTENTLAYOUT_HIGHLIGHTS],
            [self::class, self::MODULE_CONTENTLAYOUT_HIGHLIGHTS_APPENDABLE],
        );
    }
    public function getInnerSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_CONTENTLAYOUT_HIGHLIGHTS:
                return [Wassup_Module_Processor_ContentMultipleInners::class, Wassup_Module_Processor_ContentMultipleInners::MODULE_LAYOUTCONTENTINNER_HIGHLIGHTS];

            case self::MODULE_CONTENTLAYOUT_HIGHLIGHTS_APPENDABLE:
                return [Wassup_Module_Processor_ContentMultipleInners::class, Wassup_Module_Processor_ContentMultipleInners::MODULE_LAYOUTCONTENTINNER_HIGHLIGHTS_APPENDABLE];
        }

        return parent::getInnerSubmodule($module);
    }

    public function addFetchedData(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_CONTENTLAYOUT_HIGHLIGHTS:
            case self::MODULE_CONTENTLAYOUT_HIGHLIGHTS_APPENDABLE:
                return false;
        }

        return parent::addFetchedData($module, $props);
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_CONTENTLAYOUT_HIGHLIGHTS_APPENDABLE:
                $classes = array(
                    self::MODULE_CONTENTLAYOUT_HIGHLIGHTS_APPENDABLE => 'highlights',
                );

                $this->setProp($module, $props, 'appendable', true);
                $this->setProp($module, $props, 'appendable-class', $classes[$module[1]] ?? null);

                // Show the lazy loading spinner?
                // if ($this->getProp($module, $props, 'show-lazyloading-spinner')) {

                //     $this->setProp($module, $props, 'description', GD_CONSTANT_LAZYLOAD_LOADINGDIV);
                // }
                break;
        }

        parent::initModelProps($module, $props);
    }
}


