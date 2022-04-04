<?php

class UserStance_Module_Processor_LayoutContents extends PoP_Module_Processor_ContentsBase
{
    public final const MODULE_CONTENTLAYOUT_STANCES = 'contentlayout-stances';
    public final const MODULE_CONTENTLAYOUT_STANCES_APPENDABLE = 'contentlayout-stances-appendable';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_CONTENTLAYOUT_STANCES],
            [self::class, self::MODULE_CONTENTLAYOUT_STANCES_APPENDABLE],
        );
    }
    public function getInnerSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_CONTENTLAYOUT_STANCES:
                return [UserStance_Module_Processor_ContentMultipleInners::class, UserStance_Module_Processor_ContentMultipleInners::MODULE_LAYOUTCONTENTINNER_STANCES];

            case self::MODULE_CONTENTLAYOUT_STANCES_APPENDABLE:
                return [UserStance_Module_Processor_ContentMultipleInners::class, UserStance_Module_Processor_ContentMultipleInners::MODULE_LAYOUTCONTENTINNER_STANCES_APPENDABLE];
        }

        return parent::getInnerSubmodule($module);
    }

    public function addFetchedData(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_CONTENTLAYOUT_STANCES:
            case self::MODULE_CONTENTLAYOUT_STANCES_APPENDABLE:
                return false;
        }

        return parent::addFetchedData($module, $props);
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_CONTENTLAYOUT_STANCES_APPENDABLE:
                $classes = array(
                    self::MODULE_CONTENTLAYOUT_STANCES_APPENDABLE => GD_CLASS_STANCES,
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


