<?php

declare(strict_types=1);

namespace PoPSitesWassup\UserStateMutations\MutationResolverBridges;

use PoP\ComponentModel\MutationResolverBridges\AbstractComponentMutationResolverBridge;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP_Module_Processor_LoginTextFormInputs;
use PoPCMSSchema\UserStateMutations\MutationResolvers\MutationInputProperties;
use PoPSitesWassup\UserStateMutations\MutationResolvers\LoginUserByCredentialsMutationResolver;

class LoginUserByCredentialsMutationResolverBridge extends AbstractComponentMutationResolverBridge
{
    private ?LoginUserByCredentialsMutationResolver $loginUserByCredentialsMutationResolver = null;

    final public function setLoginUserByCredentialsMutationResolver(LoginUserByCredentialsMutationResolver $loginUserByCredentialsMutationResolver): void
    {
        $this->loginUserByCredentialsMutationResolver = $loginUserByCredentialsMutationResolver;
    }
    final protected function getLoginUserByCredentialsMutationResolver(): LoginUserByCredentialsMutationResolver
    {
        return $this->loginUserByCredentialsMutationResolver ??= $this->instanceManager->getInstance(LoginUserByCredentialsMutationResolver::class);
    }

    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->getLoginUserByCredentialsMutationResolver();
    }

    public function appendMutationDataToFieldDataAccessor(\PoP\ComponentModel\Mutation\FieldDataAccessorInterface $fieldDataProvider): void
    {
        $fieldDataProvider->add(MutationInputProperties::USERNAME_OR_EMAIL, trim($this->getComponentProcessorManager()->getComponentProcessor([PoP_Module_Processor_LoginTextFormInputs::class, PoP_Module_Processor_LoginTextFormInputs::COMPONENT_FORMINPUT_LOGIN_USERNAME])->getValue([PoP_Module_Processor_LoginTextFormInputs::class, PoP_Module_Processor_LoginTextFormInputs::COMPONENT_FORMINPUT_LOGIN_USERNAME])));
        $fieldDataProvider->add(MutationInputProperties::PASSWORD, $this->getComponentProcessorManager()->getComponentProcessor([PoP_Module_Processor_LoginTextFormInputs::class, PoP_Module_Processor_LoginTextFormInputs::COMPONENT_FORMINPUT_LOGIN_PWD])->getValue([PoP_Module_Processor_LoginTextFormInputs::class, PoP_Module_Processor_LoginTextFormInputs::COMPONENT_FORMINPUT_LOGIN_PWD]));
    }
}
