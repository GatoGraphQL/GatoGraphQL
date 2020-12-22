<?php

class PoP_Module_Processor_Dividers extends PoP_Module_Processor_DividersBase
{
    public const MODULE_DIVIDER = 'divider';
    public const MODULE_COLLAPSIBLEDIVIDER = 'collapsible-divider';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DIVIDER],
            [self::class, self::MODULE_COLLAPSIBLEDIVIDER],
        );
    }

    public function initModelProps(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_COLLAPSIBLEDIVIDER:
                $this->setProp($module, $props, 'class', 'collapse');
                break;
        }
        
        parent::initModelProps($module, $props);
    }
}



