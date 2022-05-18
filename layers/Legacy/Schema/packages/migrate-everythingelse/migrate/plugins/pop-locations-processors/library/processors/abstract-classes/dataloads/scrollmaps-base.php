<?php

abstract class GD_EM_Module_Processor_ScrollMapDataloadsBase extends PoP_Module_Processor_SectionDataloadsBase
{
    protected function getImmutableDataloadQueryArgs(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableDataloadQueryArgs($componentVariation, $props);
        
        // Allow to override the limit by $props
        if ($limit = $this->getProp($componentVariation, $props, 'limit')) {
            $ret['limit'] = $limit;
        }

        return $ret;
    }
}
