<?php

abstract class GD_EM_Module_Processor_ScrollMapDataloadsBase extends PoP_Module_Processor_SectionDataloadsBase
{
    protected function getImmutableDataloadQueryArgs(array $component, array &$props): array
    {
        $ret = parent::getImmutableDataloadQueryArgs($component, $props);
        
        // Allow to override the limit by $props
        if ($limit = $this->getProp($component, $props, 'limit')) {
            $ret['limit'] = $limit;
        }

        return $ret;
    }
}
