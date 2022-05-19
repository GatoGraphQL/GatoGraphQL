<?php

class PoP_SocialNetwork_UserStateModuleDecoratorProcessor extends PoP_UserStateModuleDecoratorProcessor
{
    public function requiresUserState(array $component, array &$props)
    {
        switch ($component[1]) {
            case PoP_Module_Processor_FunctionsDataloads::COMPONENT_DATALOAD_FOLLOWSUSERS:
            case PoP_Module_Processor_FunctionsDataloads::COMPONENT_DATALOAD_RECOMMENDSPOSTS:
            case PoP_Module_Processor_FunctionsDataloads::COMPONENT_DATALOAD_SUBSCRIBESTOTAGS:
            case PoP_Module_Processor_FunctionsDataloads::COMPONENT_DATALOAD_UPVOTESPOSTS:
            case PoP_Module_Processor_FunctionsDataloads::COMPONENT_DATALOAD_DOWNVOTESPOSTS:
                return true;
        }

        return parent::requiresUserState($component, $props);
    }
}

/**
 * Settings Initialization
 */
PoP_UserStateModuleDecoratorProcessorManagerFactory::getInstance()->add(PoP_Module_Processor_FunctionsDataloads::class, PoP_SocialNetwork_UserStateModuleDecoratorProcessor::class);
