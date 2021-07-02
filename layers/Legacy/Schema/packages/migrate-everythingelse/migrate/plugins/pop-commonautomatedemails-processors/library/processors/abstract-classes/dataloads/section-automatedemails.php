<?php

abstract class PoP_CommonAutomatedEmails_Module_Processor_SectionDataloadsBase extends PoP_Module_Processor_SectionDataloadsBase
{
    public function initModelProps(array $module, array &$props): void
    {

        // Do not show the filter
        $this->setProp($module, $props, 'show-filter', false);
        parent::initModelProps($module, $props);
    }
}
