<?php

class PoP_SocialNetowrkProcessors_LoginProcessorHooks
{
    public function __construct()
    {
        \PoP\Root\App::getHookManager()->addFilter(
            'PoP_Module_Processor_UserAccountUtils:login:modules',
            array($this, 'getLoginModules')
        );
    }

    public function getLoginModules($modules)
    {
        $modules[] = [PoP_Module_Processor_FunctionsDataloads::class, PoP_Module_Processor_FunctionsDataloads::MODULE_DATALOAD_FOLLOWSUSERS];
        $modules[] = [PoP_Module_Processor_FunctionsDataloads::class, PoP_Module_Processor_FunctionsDataloads::MODULE_DATALOAD_RECOMMENDSPOSTS];
        $modules[] = [PoP_Module_Processor_FunctionsDataloads::class, PoP_Module_Processor_FunctionsDataloads::MODULE_DATALOAD_SUBSCRIBESTOTAGS];
        $modules[] = [PoP_Module_Processor_FunctionsDataloads::class, PoP_Module_Processor_FunctionsDataloads::MODULE_DATALOAD_UPVOTESPOSTS];
        $modules[] = [PoP_Module_Processor_FunctionsDataloads::class, PoP_Module_Processor_FunctionsDataloads::MODULE_DATALOAD_DOWNVOTESPOSTS];
        return $modules;
    }
}

/**
 * Initialization
 */
new PoP_SocialNetowrkProcessors_LoginProcessorHooks();
