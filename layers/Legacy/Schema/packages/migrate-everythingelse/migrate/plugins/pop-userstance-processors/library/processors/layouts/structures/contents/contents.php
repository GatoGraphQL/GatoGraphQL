<?php

class UserStance_Module_Processor_LayoutContents extends PoP_Module_Processor_ContentsBase
{
    public final const MODULE_CONTENTLAYOUT_STANCES = 'contentlayout-stances';
    public final const MODULE_CONTENTLAYOUT_STANCES_APPENDABLE = 'contentlayout-stances-appendable';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_CONTENTLAYOUT_STANCES],
            [self::class, self::MODULE_CONTENTLAYOUT_STANCES_APPENDABLE],
        );
    }
    public function getInnerSubmodule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_CONTENTLAYOUT_STANCES:
                return [UserStance_Module_Processor_ContentMultipleInners::class, UserStance_Module_Processor_ContentMultipleInners::MODULE_LAYOUTCONTENTINNER_STANCES];

            case self::MODULE_CONTENTLAYOUT_STANCES_APPENDABLE:
                return [UserStance_Module_Processor_ContentMultipleInners::class, UserStance_Module_Processor_ContentMultipleInners::MODULE_LAYOUTCONTENTINNER_STANCES_APPENDABLE];
        }

        return parent::getInnerSubmodule($componentVariation);
    }

    public function addFetchedData(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_CONTENTLAYOUT_STANCES:
            case self::MODULE_CONTENTLAYOUT_STANCES_APPENDABLE:
                return false;
        }

        return parent::addFetchedData($componentVariation, $props);
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_CONTENTLAYOUT_STANCES_APPENDABLE:
                $classes = array(
                    self::MODULE_CONTENTLAYOUT_STANCES_APPENDABLE => GD_CLASS_STANCES,
                );

                $this->setProp($componentVariation, $props, 'appendable', true);
                $this->setProp($componentVariation, $props, 'appendable-class', $classes[$componentVariation[1]] ?? null);

                // Show the lazy loading spinner?
                // if ($this->getProp($componentVariation, $props, 'show-lazyloading-spinner')) {

                //     $this->setProp($componentVariation, $props, 'description', GD_CONSTANT_LAZYLOAD_LOADINGDIV);
                // }
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}


