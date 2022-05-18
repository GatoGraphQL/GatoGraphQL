<?php

abstract class PoP_Module_Processor_PostViewComponentButtonsBase extends PoP_Module_Processor_ViewComponentButtonsBase
{
    public function getDbobjectParams(array $componentVariation): array
    {
        $ret = parent::getDbobjectParams($componentVariation);

        $ret['data-target-title'] = 'title';
        $ret['data-target-url'] = 'url';
        
        return $ret;
    }
}
