<?php

class UserStance_Module_Processor_Codes extends PoP_Module_Processor_HTMLCodesBase
{
    public final const MODULE_USERSTANCE_HTMLCODE_STANCESLIDESTITLE = 'htmlcode-stanceslidestitle';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_USERSTANCE_HTMLCODE_STANCESLIDESTITLE],
        );
    }

    public function getHtmlTag(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_USERSTANCE_HTMLCODE_STANCESLIDESTITLE:
                return 'h1';
        }
    
        return parent::getHtmlTag($module, $props);
    }

    public function getCode(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_USERSTANCE_HTMLCODE_STANCESLIDESTITLE:
                return getRouteIcon(POP_USERSTANCE_ROUTE_STANCES, true).PoP_UserStanceProcessors_Utils::getLatestvotesTitle();
        }
    
        return parent::getCode($module, $props);
    }
}


