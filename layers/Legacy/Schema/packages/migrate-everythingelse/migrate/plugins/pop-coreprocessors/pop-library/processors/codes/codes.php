<?php

use PoP\ComponentModel\State\ApplicationState;

class GD_Core_Module_Processor_HTMLCodes extends PoP_Module_Processor_HTMLCodesBase
{
    public final const COMPONENT_CODE_APPSHELL = 'code-appshell';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_CODE_APPSHELL,
        );
    }

    public function getJsmethods(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);

        switch ($component->name) {
            case self::COMPONENT_CODE_APPSHELL:
                // This is all this block does: load the external url defined in parameter "url"
                $this->addJsmethod($ret, 'fetchBrowserURL', '', false, POP_PROGRESSIVEBOOTING_CRITICAL);
                break;
        }

        return $ret;
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_CODE_APPSHELL:
                // Make it invisible, nothing to show
                $this->appendProp($component, $props, 'class', 'hidden');
                break;
        }

        parent::initModelProps($component, $props);
    }
}



