<?php

class UserStance_Module_Processor_Codes extends PoP_Module_Processor_HTMLCodesBase
{
    public final const COMPONENT_USERSTANCE_HTMLCODE_STANCESLIDESTITLE = 'htmlcode-stanceslidestitle';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_USERSTANCE_HTMLCODE_STANCESLIDESTITLE,
        );
    }

    public function getHtmlTag(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_USERSTANCE_HTMLCODE_STANCESLIDESTITLE:
                return 'h1';
        }
    
        return parent::getHtmlTag($component, $props);
    }

    public function getCode(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_USERSTANCE_HTMLCODE_STANCESLIDESTITLE:
                return getRouteIcon(POP_USERSTANCE_ROUTE_STANCES, true).PoP_UserStanceProcessors_Utils::getLatestvotesTitle();
        }
    
        return parent::getCode($component, $props);
    }
}


