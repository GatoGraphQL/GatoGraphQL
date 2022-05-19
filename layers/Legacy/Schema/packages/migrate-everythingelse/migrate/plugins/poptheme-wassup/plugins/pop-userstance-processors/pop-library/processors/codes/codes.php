<?php

class UserStance_Module_Processor_Codes extends PoP_Module_Processor_HTMLCodesBase
{
    public final const COMPONENT_USERSTANCE_HTMLCODE_STANCESLIDESTITLE = 'htmlcode-stanceslidestitle';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_USERSTANCE_HTMLCODE_STANCESLIDESTITLE],
        );
    }

    public function getHtmlTag(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_USERSTANCE_HTMLCODE_STANCESLIDESTITLE:
                return 'h1';
        }
    
        return parent::getHtmlTag($component, $props);
    }

    public function getCode(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_USERSTANCE_HTMLCODE_STANCESLIDESTITLE:
                return getRouteIcon(POP_USERSTANCE_ROUTE_STANCES, true).PoP_UserStanceProcessors_Utils::getLatestvotesTitle();
        }
    
        return parent::getCode($component, $props);
    }
}


