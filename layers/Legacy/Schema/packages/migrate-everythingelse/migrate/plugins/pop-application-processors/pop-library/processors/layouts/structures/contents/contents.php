<?php

class Wassup_Module_Processor_LayoutContents extends PoP_Module_Processor_ContentsBase
{
    public final const COMPONENT_CONTENTLAYOUT_HIGHLIGHTS = 'contentlayout-highlights';
    public final const COMPONENT_CONTENTLAYOUT_HIGHLIGHTS_APPENDABLE = 'contentlayout-highlights-appendable';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_CONTENTLAYOUT_HIGHLIGHTS],
            [self::class, self::COMPONENT_CONTENTLAYOUT_HIGHLIGHTS_APPENDABLE],
        );
    }
    public function getInnerSubcomponent(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_CONTENTLAYOUT_HIGHLIGHTS:
                return [Wassup_Module_Processor_ContentMultipleInners::class, Wassup_Module_Processor_ContentMultipleInners::COMPONENT_LAYOUTCONTENTINNER_HIGHLIGHTS];

            case self::COMPONENT_CONTENTLAYOUT_HIGHLIGHTS_APPENDABLE:
                return [Wassup_Module_Processor_ContentMultipleInners::class, Wassup_Module_Processor_ContentMultipleInners::COMPONENT_LAYOUTCONTENTINNER_HIGHLIGHTS_APPENDABLE];
        }

        return parent::getInnerSubcomponent($component);
    }

    public function addFetchedData(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_CONTENTLAYOUT_HIGHLIGHTS:
            case self::COMPONENT_CONTENTLAYOUT_HIGHLIGHTS_APPENDABLE:
                return false;
        }

        return parent::addFetchedData($component, $props);
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_CONTENTLAYOUT_HIGHLIGHTS_APPENDABLE:
                $classes = array(
                    self::COMPONENT_CONTENTLAYOUT_HIGHLIGHTS_APPENDABLE => 'highlights',
                );

                $this->setProp($component, $props, 'appendable', true);
                $this->setProp($component, $props, 'appendable-class', $classes[$component[1]] ?? null);

                // Show the lazy loading spinner?
                // if ($this->getProp($component, $props, 'show-lazyloading-spinner')) {

                //     $this->setProp($component, $props, 'description', GD_CONSTANT_LAZYLOAD_LOADINGDIV);
                // }
                break;
        }

        parent::initModelProps($component, $props);
    }
}


