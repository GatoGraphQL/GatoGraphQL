<?php

declare(strict_types=1);

namespace PoPSitesWassup\UserStateMutations\MutationResolverBridges;

use PoP_Module_Processor_LoginTextFormInputs;
use PoP\ComponentModel\MutationResolverBridges\AbstractComponentMutationResolverBridge;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\QueryInputOutputHandlers\ResponseConstants;
use PoP\Engine\Route\RouteUtils;
use PoPSitesWassup\UserStateMutations\MutationResolvers\LostPasswordMutationResolver;
use PoPSitesWassup\UserStateMutations\MutationResolvers\MutationInputProperties;

class LostPasswordMutationResolverBridge extends AbstractComponentMutationResolverBridge
{
    private ?LostPasswordMutationResolver $lostPasswordMutationResolver = null;

    final public function setLostPasswordMutationResolver(LostPasswordMutationResolver $lostPasswordMutationResolver): void
    {
        $this->lostPasswordMutationResolver = $lostPasswordMutationResolver;
    }
    final protected function getLostPasswordMutationResolver(): LostPasswordMutationResolver
    {
        return $this->lostPasswordMutationResolver ??= $this->instanceManager->getInstance(LostPasswordMutationResolver::class);
    }

    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->getLostPasswordMutationResolver();
    }

    public function getFormData(): array
    {
        return [
            MutationInputProperties::USERNAME_OR_EMAIL => $this->getComponentProcessorManager()->getProcessor([PoP_Module_Processor_LoginTextFormInputs::class, PoP_Module_Processor_LoginTextFormInputs::COMPONENT_FORMINPUT_LOSTPWD_USERNAME])->getValue([PoP_Module_Processor_LoginTextFormInputs::class, PoP_Module_Processor_LoginTextFormInputs::COMPONENT_FORMINPUT_LOSTPWD_USERNAME]),
        ];
    }

    /**
     * @return array<string, mixed>|null
     */
    public function executeMutation(array &$data_properties): ?array
    {
        $executed = parent::executeMutation($data_properties);
        if ($executed && is_array($executed) && $executed[ResponseConstants::SUCCESS]) {
            // Redirect to the "Reset password" page
            $executed[GD_DATALOAD_QUERYHANDLERRESPONSE_SOFTREDIRECT] = RouteUtils::getRouteURL(POP_USERLOGIN_ROUTE_LOSTPWDRESET);
        }
        return $executed;
    }
}
