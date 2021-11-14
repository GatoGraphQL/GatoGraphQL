<?php

declare(strict_types=1);

namespace PoPSitesWassup\UserStateMutations\MutationResolverBridges;

use PoP\ComponentModel\MutationResolverBridges\AbstractComponentMutationResolverBridge;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSchema\UserStateMutations\MutationResolvers\MutationInputProperties;
use PoPSitesWassup\UserStateMutations\MutationResolvers\WebsiteLoginMutationResolver;

class WebsiteLoginMutationResolverBridge extends AbstractComponentMutationResolverBridge
{
    private ?WebsiteLoginMutationResolver $websiteLoginMutationResolver = null;

    final public function setWebsiteLoginMutationResolver(WebsiteLoginMutationResolver $websiteLoginMutationResolver): void
    {
        $this->websiteLoginMutationResolver = $websiteLoginMutationResolver;
    }
    final protected function getWebsiteLoginMutationResolver(): WebsiteLoginMutationResolver
    {
        return $this->websiteLoginMutationResolver ??= $this->instanceManager->getInstance(WebsiteLoginMutationResolver::class);
    }

    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->getWebsiteLoginMutationResolver();
    }

    public function getFormData(): array
    {
        return [
            MutationInputProperties::USERNAME_OR_EMAIL => trim($this->getModuleProcessorManager()->getProcessor([\PoP_Module_Processor_LoginTextFormInputs::class, \PoP_Module_Processor_LoginTextFormInputs::MODULE_FORMINPUT_LOGIN_USERNAME])->getValue([\PoP_Module_Processor_LoginTextFormInputs::class, \PoP_Module_Processor_LoginTextFormInputs::MODULE_FORMINPUT_LOGIN_USERNAME])),
            MutationInputProperties::PASSWORD => $this->getModuleProcessorManager()->getProcessor([\PoP_Module_Processor_LoginTextFormInputs::class, \PoP_Module_Processor_LoginTextFormInputs::MODULE_FORMINPUT_LOGIN_PWD])->getValue([\PoP_Module_Processor_LoginTextFormInputs::class, \PoP_Module_Processor_LoginTextFormInputs::MODULE_FORMINPUT_LOGIN_PWD]),
        ];
    }
}
