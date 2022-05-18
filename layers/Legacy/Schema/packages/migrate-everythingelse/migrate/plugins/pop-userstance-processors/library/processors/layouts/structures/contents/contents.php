<?php

class UserStance_Module_Processor_LayoutContents extends PoP_Module_Processor_ContentsBase
{
    public final const COMPONENT_CONTENTLAYOUT_STANCES = 'contentlayout-stances';
    public final const COMPONENT_CONTENTLAYOUT_STANCES_APPENDABLE = 'contentlayout-stances-appendable';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_CONTENTLAYOUT_STANCES],
            [self::class, self::COMPONENT_CONTENTLAYOUT_STANCES_APPENDABLE],
        );
    }
    public function getInnerSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_CONTENTLAYOUT_STANCES:
                return [UserStance_Module_Processor_ContentMultipleInners::class, UserStance_Module_Processor_ContentMultipleInners::COMPONENT_LAYOUTCONTENTINNER_STANCES];

            case self::COMPONENT_CONTENTLAYOUT_STANCES_APPENDABLE:
                return [UserStance_Module_Processor_ContentMultipleInners::class, UserStance_Module_Processor_ContentMultipleInners::COMPONENT_LAYOUTCONTENTINNER_STANCES_APPENDABLE];
        }

        return parent::getInnerSubmodule($component);
    }

    public function addFetchedData(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_CONTENTLAYOUT_STANCES:
            case self::COMPONENT_CONTENTLAYOUT_STANCES_APPENDABLE:
                return false;
        }

        return parent::addFetchedData($component, $props);
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_CONTENTLAYOUT_STANCES_APPENDABLE:
                $classes = array(
                    self::COMPONENT_CONTENTLAYOUT_STANCES_APPENDABLE => GD_CLASS_STANCES,
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


