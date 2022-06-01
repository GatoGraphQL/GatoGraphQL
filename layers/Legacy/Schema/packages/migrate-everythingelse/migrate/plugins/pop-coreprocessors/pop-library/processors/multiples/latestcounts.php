<?php

class GD_Core_Module_Processor_Blocks extends PoP_Module_Processor_MultiplesBase
{
    public final const COMPONENT_MULTIPLE_LATESTCOUNTS = 'multiple-latestcounts';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_MULTIPLE_LATESTCOUNTS],
        );
    }

    public function getSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getSubcomponents($component);

        $inner_components = array(
            self::COMPONENT_MULTIPLE_LATESTCOUNTS => [GD_Core_Module_Processor_Dataloads::class, GD_Core_Module_Processor_Dataloads::COMPONENT_DATALOAD_LATESTCOUNTS],
        );
        if ($inner = $inner_components[$component->name] ?? null) {
            $ret[] = $inner;
        }

        return $ret;
    }

    public function getJsmethods(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);

        switch ($component->name) {
            case self::COMPONENT_MULTIPLE_LATESTCOUNTS:
                // Fetch latest notifications every 30 seconds
                $this->addJsmethod($ret, 'timeoutLoadLatestBlock');

                // Allow Service Workers to inject js function 'resetTimestamp'
                if ($extra_jsmethods = \PoP\Root\App::applyFilters('GD_Core_Module_Processor_Blocks:jsmethod:latestcounts', array())) {
                    foreach ($extra_jsmethods as $extra_jsmethod) {
                        $this->addJsmethod($ret, $extra_jsmethod);
                    }
                }
                break;
        }

        return $ret;
    }
}



