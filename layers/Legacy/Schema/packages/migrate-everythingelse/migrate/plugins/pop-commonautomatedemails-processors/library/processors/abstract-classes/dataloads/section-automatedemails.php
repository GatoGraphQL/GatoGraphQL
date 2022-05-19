<?php

abstract class PoP_CommonAutomatedEmails_Module_Processor_SectionDataloadsBase extends PoP_Module_Processor_SectionDataloadsBase
{
    public function initModelProps(array $component, array &$props): void
    {

        // Do not show the filter
        $this->setProp($component, $props, 'show-filter', false);
        parent::initModelProps($component, $props);
    }
}
