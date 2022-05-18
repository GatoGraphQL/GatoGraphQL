<?php

abstract class PoP_Module_Processor_CustomDelegatorFiltersBase extends PoP_Module_Processor_DelegatorFiltersBase
{
    public function getBlockTarget(array $component, array &$props)
    {

        // The proxied block is in the Main PageSection
        // Comment Leo 10/12/2016: in the past, we did .active, however that doesn't work anymore for when alt+click to open a link, instead must pick the last added .tab-pane with selector "last-child"
        // Comment Leo 12/01/2017: Actually, for the forms we must use .active instead of :last-child, because the selector is executed
        // on runtime, and not when initializing the JS
        return '#'.POP_COMPONENTID_PAGESECTIONCONTAINERID_BODY.' .pop-pagesection-page.toplevel.active > .pop-block.withfilter';
    }

    public function getClasses(array $component, array &$props)
    {
        return 'alert alert-info';
    }

    public function initModelProps(array $component, array &$props): void
    {
        $this->appendProp($component, $props, 'class', $this->getClasses($component, $props));
        parent::initModelProps($component, $props);
    }
}
