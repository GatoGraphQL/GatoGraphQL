<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolverBridges;

use PoP\GraphQLParser\Spec\Parser\Ast\WithArgumentsInterface;
use PoP_Module_Processor_CreateUpdateUserTextFormInputs;
use PoP\Root\App;
use PoP\ComponentModel\MutationResolverBridges\AbstractComponentMutationResolverBridge;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolvers\ChangeUserPasswordMutationResolver;

class ChangeUserPasswordMutationResolverBridge extends AbstractComponentMutationResolverBridge
{
    private ?ChangeUserPasswordMutationResolver $changeUserPasswordMutationResolver = null;
    
    final public function setChangeUserPasswordMutationResolver(ChangeUserPasswordMutationResolver $changeUserPasswordMutationResolver): void
    {
        $this->changeUserPasswordMutationResolver = $changeUserPasswordMutationResolver;
    }
    final protected function getChangeUserPasswordMutationResolver(): ChangeUserPasswordMutationResolver
    {
        return $this->changeUserPasswordMutationResolver ??= $this->instanceManager->getInstance(ChangeUserPasswordMutationResolver::class);
    }
    
    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->getChangeUserPasswordMutationResolver();
    }

    public function addArgumentsForMutation(WithArgumentsInterface $withArgumentsAST): void
    {
        $user_id = App::getState('current-user-id');
        
        $withArgumentsAST->addArgument(new \PoP\GraphQLParser\Spec\Parser\Ast\Argument('user_id', $user_id, \PoP\GraphQLParser\StaticHelpers\LocationHelper::getNonSpecificLocation()));
        $withArgumentsAST->addArgument(new \PoP\GraphQLParser\Spec\Parser\Ast\Argument('current_password', $this->getComponentProcessorManager()->getComponentProcessor([PoP_Module_Processor_CreateUpdateUserTextFormInputs::class, PoP_Module_Processor_CreateUpdateUserTextFormInputs::COMPONENT_FORMINPUT_CUU_CURRENTPASSWORD])->getValue([PoP_Module_Processor_CreateUpdateUserTextFormInputs::class, PoP_Module_Processor_CreateUpdateUserTextFormInputs::COMPONENT_FORMINPUT_CUU_CURRENTPASSWORD]), \PoP\GraphQLParser\StaticHelpers\LocationHelper::getNonSpecificLocation()));
        $withArgumentsAST->addArgument(new \PoP\GraphQLParser\Spec\Parser\Ast\Argument('password', $this->getComponentProcessorManager()->getComponentProcessor([PoP_Module_Processor_CreateUpdateUserTextFormInputs::class, PoP_Module_Processor_CreateUpdateUserTextFormInputs::COMPONENT_FORMINPUT_CUU_NEWPASSWORD])->getValue([PoP_Module_Processor_CreateUpdateUserTextFormInputs::class, PoP_Module_Processor_CreateUpdateUserTextFormInputs::COMPONENT_FORMINPUT_CUU_NEWPASSWORD]), \PoP\GraphQLParser\StaticHelpers\LocationHelper::getNonSpecificLocation()));
        $withArgumentsAST->addArgument(new \PoP\GraphQLParser\Spec\Parser\Ast\Argument('repeat_password', $this->getComponentProcessorManager()->getComponentProcessor([PoP_Module_Processor_CreateUpdateUserTextFormInputs::class, PoP_Module_Processor_CreateUpdateUserTextFormInputs::COMPONENT_FORMINPUT_CUU_NEWPASSWORDREPEAT])->getValue([PoP_Module_Processor_CreateUpdateUserTextFormInputs::class, PoP_Module_Processor_CreateUpdateUserTextFormInputs::COMPONENT_FORMINPUT_CUU_NEWPASSWORDREPEAT], \PoP\GraphQLParser\StaticHelpers\LocationHelper::getNonSpecificLocation()));
    }
}
