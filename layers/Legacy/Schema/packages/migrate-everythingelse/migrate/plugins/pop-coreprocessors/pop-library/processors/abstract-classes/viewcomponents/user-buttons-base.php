<?php

abstract class PoP_Module_Processor_UserViewComponentButtonsBase extends PoP_Module_Processor_ViewComponentButtonsBase
{
    public function getDbobjectParams(array $component): array
    {
        $ret = parent::getDbobjectParams($component);

        $ret['data-target-title'] = 'displayName';
        $ret['data-target-url'] = 'url';
        
        return $ret;
    }
}
