<?php

class PoP_SocialNetowrkProcessors_LoginProcessorHooks
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            'PoP_Module_Processor_UserAccountUtils:login:modules',
            $this->getLoginComponentVariations(...)
        );
    }

    public function getLoginComponentVariations($componentVariations)
    {
        $componentVariations[] = [PoP_Module_Processor_FunctionsDataloads::class, PoP_Module_Processor_FunctionsDataloads::MODULE_DATALOAD_FOLLOWSUSERS];
        $componentVariations[] = [PoP_Module_Processor_FunctionsDataloads::class, PoP_Module_Processor_FunctionsDataloads::MODULE_DATALOAD_RECOMMENDSPOSTS];
        $componentVariations[] = [PoP_Module_Processor_FunctionsDataloads::class, PoP_Module_Processor_FunctionsDataloads::MODULE_DATALOAD_SUBSCRIBESTOTAGS];
        $componentVariations[] = [PoP_Module_Processor_FunctionsDataloads::class, PoP_Module_Processor_FunctionsDataloads::MODULE_DATALOAD_UPVOTESPOSTS];
        $componentVariations[] = [PoP_Module_Processor_FunctionsDataloads::class, PoP_Module_Processor_FunctionsDataloads::MODULE_DATALOAD_DOWNVOTESPOSTS];
        return $componentVariations;
    }
}

/**
 * Initialization
 */
new PoP_SocialNetowrkProcessors_LoginProcessorHooks();
