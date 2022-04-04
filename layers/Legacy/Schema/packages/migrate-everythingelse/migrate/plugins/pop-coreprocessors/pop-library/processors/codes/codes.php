<?php

use PoP\ComponentModel\State\ApplicationState;

class GD_Core_Module_Processor_HTMLCodes extends PoP_Module_Processor_HTMLCodesBase
{
    public final const MODULE_CODE_APPSHELL = 'code-appshell';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_CODE_APPSHELL],
        );
    }

    public function getJsmethods(array $module, array &$props)
    {
        $ret = parent::getJsmethods($module, $props);

        switch ($module[1]) {
            case self::MODULE_CODE_APPSHELL:
                // This is all this block does: load the external url defined in parameter "url"
                $this->addJsmethod($ret, 'fetchBrowserURL', '', false, POP_PROGRESSIVEBOOTING_CRITICAL);
                break;
        }

        return $ret;
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_CODE_APPSHELL:
                // Make it invisible, nothing to show
                $this->appendProp($module, $props, 'class', 'hidden');
                break;
        }

        parent::initModelProps($module, $props);
    }
}



