<?php

use PoP\ComponentModel\State\ApplicationState;

class GD_Core_Module_Processor_HTMLCodes extends PoP_Module_Processor_HTMLCodesBase
{
    public final const MODULE_CODE_APPSHELL = 'code-appshell';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_CODE_APPSHELL],
        );
    }

    public function getJsmethods(array $componentVariation, array &$props)
    {
        $ret = parent::getJsmethods($componentVariation, $props);

        switch ($componentVariation[1]) {
            case self::MODULE_CODE_APPSHELL:
                // This is all this block does: load the external url defined in parameter "url"
                $this->addJsmethod($ret, 'fetchBrowserURL', '', false, POP_PROGRESSIVEBOOTING_CRITICAL);
                break;
        }

        return $ret;
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_CODE_APPSHELL:
                // Make it invisible, nothing to show
                $this->appendProp($componentVariation, $props, 'class', 'hidden');
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}



