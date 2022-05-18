<?php

class GD_Core_Module_Processor_Blocks extends PoP_Module_Processor_MultiplesBase
{
    public final const MODULE_MULTIPLE_LATESTCOUNTS = 'multiple-latestcounts';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_MULTIPLE_LATESTCOUNTS],
        );
    }

    public function getSubComponentVariations(array $componentVariation): array
    {
        $ret = parent::getSubComponentVariations($componentVariation);

        $inner_modules = array(
            self::MODULE_MULTIPLE_LATESTCOUNTS => [GD_Core_Module_Processor_Dataloads::class, GD_Core_Module_Processor_Dataloads::MODULE_DATALOAD_LATESTCOUNTS],
        );
        if ($inner = $inner_modules[$componentVariation[1]] ?? null) {
            $ret[] = $inner;
        }

        return $ret;
    }

    public function getJsmethods(array $componentVariation, array &$props)
    {
        $ret = parent::getJsmethods($componentVariation, $props);

        switch ($componentVariation[1]) {
            case self::MODULE_MULTIPLE_LATESTCOUNTS:
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



