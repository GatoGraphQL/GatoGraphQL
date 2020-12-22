<?php

declare(strict_types=1);

namespace PoPSitesWassup\UserStateMutations\MutationResolverBridges;

use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;
use PoPSitesWassup\UserStateMutations\MutationResolvers\LoginMutationResolver;
use PoPSchema\UserStateMutations\MutationResolvers\MutationInputProperties;
use PoP\ComponentModel\MutationResolverBridges\AbstractComponentMutationResolverBridge;

class LoginMutationResolverBridge extends AbstractComponentMutationResolverBridge
{
    public function getMutationResolverClass(): string
    {
        return LoginMutationResolver::class;
    }

    public function getFormData(): array
    {
        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();
        return [
            MutationInputProperties::USERNAME_OR_EMAIL => trim($moduleprocessor_manager->getProcessor([PoP_Module_Processor_LoginTextFormInputs::class, PoP_Module_Processor_LoginTextFormInputs::MODULE_FORMINPUT_LOGIN_USERNAME])->getValue([PoP_Module_Processor_LoginTextFormInputs::class, PoP_Module_Processor_LoginTextFormInputs::MODULE_FORMINPUT_LOGIN_USERNAME])),
            MutationInputProperties::PASSWORD => $moduleprocessor_manager->getProcessor([PoP_Module_Processor_LoginTextFormInputs::class, PoP_Module_Processor_LoginTextFormInputs::MODULE_FORMINPUT_LOGIN_PWD])->getValue([PoP_Module_Processor_LoginTextFormInputs::class, PoP_Module_Processor_LoginTextFormInputs::MODULE_FORMINPUT_LOGIN_PWD]),
        ];
    }
}
