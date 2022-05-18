<?php

class PoP_Module_Processor_MultidomainCodes extends PoP_Module_Processor_HTMLCodesBase
{
    public final const MODULE_CODE_EXTERNAL = 'code-external';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_CODE_EXTERNAL],
        );
    }

    public function getRelevantRoute(array $componentVariation, array &$props): ?string
    {
        return match($componentVariation[1]) {
            self::MODULE_CODE_EXTERNAL => POP_MULTIDOMAIN_ROUTE_EXTERNAL,
            default => parent::getRelevantRoute($componentVariation, $props),
        };
    }

    public function getJsmethods(array $componentVariation, array &$props)
    {
        $ret = parent::getJsmethods($componentVariation, $props);

        switch ($componentVariation[1]) {
            case self::MODULE_CODE_EXTERNAL:
                // This is all this block does: load the external url defined in parameter "url"
                $this->addJsmethod($ret, 'clickURLParam');
                break;
        }

        return $ret;
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_CODE_EXTERNAL:
                // Make it invisible, nothing to show
                $this->appendProp($componentVariation, $props, 'class', 'hidden');
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}



