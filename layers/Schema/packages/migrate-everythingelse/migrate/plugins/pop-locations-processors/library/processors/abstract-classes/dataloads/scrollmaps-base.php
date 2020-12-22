<?php

abstract class GD_EM_Module_Processor_ScrollMapDataloadsBase extends PoP_Module_Processor_SectionDataloadsBase
{
    protected function getImmutableDataloadQueryArgs(array $module, array &$props): array
    {
        $ret = parent::getImmutableDataloadQueryArgs($module, $props);
        
        // Allow to override the limit by $props
        if ($limit = $this->getProp($module, $props, 'limit')) {
            $ret['limit'] = $limit;
        }

        return $ret;
    }
}
