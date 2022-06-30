<?php

declare(strict_types=1);

namespace PoPSitesWassup\UserStateMutations\MutationResolverBridges;

use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\ComponentModel\MutationResolverBridges\AbstractComponentMutationResolverBridge;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Literal;
use PoP\GraphQLParser\StaticHelpers\LocationHelper;
use PoP_Module_Processor_LoginTextFormInputs;
use PoPSitesWassup\UserStateMutations\MutationResolvers\MutationInputProperties;
use PoPSitesWassup\UserStateMutations\MutationResolvers\ResetLostPasswordMutationResolver;

class ResetLostPasswordMutationResolverBridge extends AbstractComponentMutationResolverBridge
{
    private ?ResetLostPasswordMutationResolver $resetLostPasswordMutationResolver = null;

    final public function setResetLostPasswordMutationResolver(ResetLostPasswordMutationResolver $resetLostPasswordMutationResolver): void
    {
        $this->resetLostPasswordMutationResolver = $resetLostPasswordMutationResolver;
    }
    final protected function getResetLostPasswordMutationResolver(): ResetLostPasswordMutationResolver
    {
        return $this->resetLostPasswordMutationResolver ??= $this->instanceManager->getInstance(ResetLostPasswordMutationResolver::class);
    }

    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->getResetLostPasswordMutationResolver();
    }

    public function addArgumentsForMutation(FieldInterface $mutationField): void
    {
        $mutationField->addArgument(new Argument(MutationInputProperties::CODE, new Literal(trim($this->getComponentProcessorManager()->getComponentProcessor([PoP_Module_Processor_LoginTextFormInputs::class, PoP_Module_Processor_LoginTextFormInputs::COMPONENT_FORMINPUT_LOSTPWDRESET_CODE])->getValue([PoP_Module_Processor_LoginTextFormInputs::class, PoP_Module_Processor_LoginTextFormInputs::COMPONENT_FORMINPUT_LOSTPWDRESET_CODE])), LocationHelper::getNonSpecificLocation()), LocationHelper::getNonSpecificLocation()));
        $mutationField->addArgument(new Argument(MutationInputProperties::PASSWORD, new Literal(trim($this->getComponentProcessorManager()->getComponentProcessor([PoP_Module_Processor_LoginTextFormInputs::class, PoP_Module_Processor_LoginTextFormInputs::COMPONENT_FORMINPUT_LOSTPWDRESET_NEWPASSWORD])->getValue([PoP_Module_Processor_LoginTextFormInputs::class, PoP_Module_Processor_LoginTextFormInputs::COMPONENT_FORMINPUT_LOSTPWDRESET_NEWPASSWORD])), LocationHelper::getNonSpecificLocation()), LocationHelper::getNonSpecificLocation()));
        $mutationField->addArgument(new Argument(MutationInputProperties::REPEAT_PASSWORD, new Literal(trim($this->getComponentProcessorManager()->getComponentProcessor([PoP_Module_Processor_LoginTextFormInputs::class, PoP_Module_Processor_LoginTextFormInputs::COMPONENT_FORMINPUT_LOSTPWDRESET_PASSWORDREPEAT])->getValue([PoP_Module_Processor_LoginTextFormInputs::class, PoP_Module_Processor_LoginTextFormInputs::COMPONENT_FORMINPUT_LOSTPWDRESET_PASSWORDREPEAT])), LocationHelper::getNonSpecificLocation()), LocationHelper::getNonSpecificLocation()));
    }
}
