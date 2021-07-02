<?php

use PoP\ComponentModel\ModuleProcessors\FormComponentModuleProcessorInterface as UpstreamFormComponentModuleProcessorInterface;

interface FormComponentModuleProcessorInterface extends UpstreamFormComponentModuleProcessorInterface
{
    public function getLabel(array $module, array &$props);
}
