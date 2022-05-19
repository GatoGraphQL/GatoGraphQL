<?php

use PoP\ComponentModel\State\ApplicationState;

class GD_Core_Module_Processor_HTMLCodes extends PoP_Module_Processor_HTMLCodesBase
{
    public final const COMPONENT_CODE_APPSHELL = 'code-appshell';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_CODE_APPSHELL],
        );
    }

    public function getJsmethods(array $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);

        switch ($component[1]) {
            case self::COMPONENT_CODE_APPSHELL:
                // This is all this block does: load the external url defined in parameter "url"
                $this->addJsmethod($ret, 'fetchBrowserURL', '', false, POP_PROGRESSIVEBOOTING_CRITICAL);
                break;
        }

        return $ret;
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_CODE_APPSHELL:
                // Make it invisible, nothing to show
                $this->appendProp($component, $props, 'class', 'hidden');
                break;
        }

        parent::initModelProps($component, $props);
    }
}



