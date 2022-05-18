<?php

class PoP_SocialNetwork_UserStateModuleDecoratorProcessor extends PoP_UserStateModuleDecoratorProcessor
{
    public function requiresUserState(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case PoP_Module_Processor_FunctionsDataloads::MODULE_DATALOAD_FOLLOWSUSERS:
            case PoP_Module_Processor_FunctionsDataloads::MODULE_DATALOAD_RECOMMENDSPOSTS:
            case PoP_Module_Processor_FunctionsDataloads::MODULE_DATALOAD_SUBSCRIBESTOTAGS:
            case PoP_Module_Processor_FunctionsDataloads::MODULE_DATALOAD_UPVOTESPOSTS:
            case PoP_Module_Processor_FunctionsDataloads::MODULE_DATALOAD_DOWNVOTESPOSTS:
                return true;
        }

        return parent::requiresUserState($componentVariation, $props);
    }
}

/**
 * Settings Initialization
 */
PoP_UserStateModuleDecoratorProcessorManagerFactory::getInstance()->add(PoP_Module_Processor_FunctionsDataloads::class, PoP_SocialNetwork_UserStateModuleDecoratorProcessor::class);
