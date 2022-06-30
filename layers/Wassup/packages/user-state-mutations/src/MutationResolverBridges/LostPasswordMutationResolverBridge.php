<?php

declare(strict_types=1);

namespace PoPSitesWassup\UserStateMutations\MutationResolverBridges;

use PoP\ComponentModel\MutationResolverBridges\AbstractComponentMutationResolverBridge;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\QueryInputOutputHandlers\ResponseConstants;
use PoP\Engine\Route\RouteUtils;
use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Literal;
use PoP\GraphQLParser\StaticHelpers\LocationHelper;
use PoP_Module_Processor_LoginTextFormInputs;
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

    public function addArgumentsForMutation(\PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface $mutationField): void
    {
        $mutationField->addArgument(new Argument(MutationInputProperties::USERNAME_OR_EMAIL, new Literal($this->getComponentProcessorManager()->getComponentProcessor([PoP_Module_Processor_LoginTextFormInputs::class, PoP_Module_Processor_LoginTextFormInputs::COMPONENT_FORMINPUT_LOSTPWD_USERNAME])->getValue([PoP_Module_Processor_LoginTextFormInputs::class, PoP_Module_Processor_LoginTextFormInputs::COMPONENT_FORMINPUT_LOSTPWD_USERNAME]), LocationHelper::getNonSpecificLocation()), LocationHelper::getNonSpecificLocation()));
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
