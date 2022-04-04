<?php
use PoP\ConfigurationComponentModel\ModuleProcessors\AbstractModuleProcessor;

class PoP_ConfigurationComponentModel_Module_Processor_Elements extends AbstractModuleProcessor
{
    public final const MODULE_EMPTY = 'empty';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_EMPTY],
        );
    }
}
