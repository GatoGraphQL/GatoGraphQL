<?php

declare(strict_types=1);

namespace PoPSitesWassup\UserStateMutations\MutationResolverBridges;

use PoP\ComponentModel\MutationResolverBridges\AbstractComponentMutationResolverBridge;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSchema\UserStateMutations\MutationResolvers\MutationInputProperties;
use PoPSitesWassup\UserStateMutations\MutationResolvers\LoginMutationResolver;
use Symfony\Contracts\Service\Attribute\Required;

class LoginMutationResolverBridge extends AbstractComponentMutationResolverBridge
{
    protected LoginMutationResolver $loginMutationResolver;

    #[Required]
    final public function autowireLoginMutationResolverBridge(
        LoginMutationResolver $loginMutationResolver,
    ): void {
        $this->loginMutationResolver = $loginMutationResolver;
    }

    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->loginMutationResolver;
    }

    public function getFormData(): array
    {
        return [
            MutationInputProperties::USERNAME_OR_EMAIL => trim($this->moduleProcessorManager->getProcessor([PoP_Module_Processor_LoginTextFormInputs::class, PoP_Module_Processor_LoginTextFormInputs::MODULE_FORMINPUT_LOGIN_USERNAME])->getValue([PoP_Module_Processor_LoginTextFormInputs::class, PoP_Module_Processor_LoginTextFormInputs::MODULE_FORMINPUT_LOGIN_USERNAME])),
            MutationInputProperties::PASSWORD => $this->moduleProcessorManager->getProcessor([PoP_Module_Processor_LoginTextFormInputs::class, PoP_Module_Processor_LoginTextFormInputs::MODULE_FORMINPUT_LOGIN_PWD])->getValue([PoP_Module_Processor_LoginTextFormInputs::class, PoP_Module_Processor_LoginTextFormInputs::MODULE_FORMINPUT_LOGIN_PWD]),
        ];
    }
}
