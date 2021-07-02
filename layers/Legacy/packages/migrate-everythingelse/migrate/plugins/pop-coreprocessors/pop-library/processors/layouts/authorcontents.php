<?php

class PoP_Module_Processor_AuthorContentLayouts extends PoP_Module_Processor_AuthorContentLayoutsBase
{
    public const MODULE_LAYOUTAUTHOR_CONTENT = 'layoutauthor-content';
    public const MODULE_LAYOUTAUTHOR_LIMITEDCONTENT = 'layoutauthor-limitedcontent';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUTAUTHOR_LIMITEDCONTENT],
            [self::class, self::MODULE_LAYOUTAUTHOR_CONTENT],
        );
    }

    public function getDescriptionMaxlength(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUTAUTHOR_LIMITEDCONTENT:
                return 300;
        }

        return parent::getDescriptionMaxlength($module, $props);
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUTAUTHOR_CONTENT:
                $this->appendProp($module, $props, 'class', 'layoutauthor readable clearfix');
                break;
        }

        parent::initModelProps($module, $props);
    }
}



