<?php

class PoP_SocialNetowrkProcessors_LoginProcessorHooks
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            'PoP_Module_Processor_UserAccountUtils:login:modules',
            $this->getLoginComponents(...)
        );
    }

    public function getLoginComponents($components)
    {
        $components[] = [PoP_Module_Processor_FunctionsDataloads::class, PoP_Module_Processor_FunctionsDataloads::COMPONENT_DATALOAD_FOLLOWSUSERS];
        $components[] = [PoP_Module_Processor_FunctionsDataloads::class, PoP_Module_Processor_FunctionsDataloads::COMPONENT_DATALOAD_RECOMMENDSPOSTS];
        $components[] = [PoP_Module_Processor_FunctionsDataloads::class, PoP_Module_Processor_FunctionsDataloads::COMPONENT_DATALOAD_SUBSCRIBESTOTAGS];
        $components[] = [PoP_Module_Processor_FunctionsDataloads::class, PoP_Module_Processor_FunctionsDataloads::COMPONENT_DATALOAD_UPVOTESPOSTS];
        $components[] = [PoP_Module_Processor_FunctionsDataloads::class, PoP_Module_Processor_FunctionsDataloads::COMPONENT_DATALOAD_DOWNVOTESPOSTS];
        return $components;
    }
}

/**
 * Initialization
 */
new PoP_SocialNetowrkProcessors_LoginProcessorHooks();
