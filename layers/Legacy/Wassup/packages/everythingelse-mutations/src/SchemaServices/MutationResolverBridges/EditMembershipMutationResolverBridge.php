<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolverBridges;

use PoP\GraphQLParser\Spec\Parser\Ast\WithArgumentsInterface;
use GD_URE_Module_Processor_ProfileMultiSelectFormInputs;
use GD_URE_Module_Processor_SelectFormInputs;
use PoP\Root\App;
use PoP\ComponentModel\MutationResolverBridges\AbstractComponentMutationResolverBridge;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPCMSSchema\Users\Constants\InputNames;
use PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolvers\EditMembershipMutationResolver;

class EditMembershipMutationResolverBridge extends AbstractComponentMutationResolverBridge
{
    private ?EditMembershipMutationResolver $editMembershipMutationResolver = null;
    
    final public function setEditMembershipMutationResolver(EditMembershipMutationResolver $editMembershipMutationResolver): void
    {
        $this->editMembershipMutationResolver = $editMembershipMutationResolver;
    }
    final protected function getEditMembershipMutationResolver(): EditMembershipMutationResolver
    {
        return $this->editMembershipMutationResolver ??= $this->instanceManager->getInstance(EditMembershipMutationResolver::class);
    }
    
    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->getEditMembershipMutationResolver();
    }

    public function addArgumentsForMutation(WithArgumentsInterface $withArgumentsAST): void
    {
        $community = App::getState('is-user-logged-in') ? App::getState('current-user-id') : '';
        $privileges = $this->getComponentProcessorManager()->getComponentProcessor([GD_URE_Module_Processor_ProfileMultiSelectFormInputs::class, GD_URE_Module_Processor_ProfileMultiSelectFormInputs::COMPONENT_URE_FORMINPUT_MEMBERPRIVILEGES])->getValue([GD_URE_Module_Processor_ProfileMultiSelectFormInputs::class, GD_URE_Module_Processor_ProfileMultiSelectFormInputs::COMPONENT_URE_FORMINPUT_MEMBERPRIVILEGES]);
        $tags = $this->getComponentProcessorManager()->getComponentProcessor([GD_URE_Module_Processor_ProfileMultiSelectFormInputs::class, GD_URE_Module_Processor_ProfileMultiSelectFormInputs::COMPONENT_URE_FORMINPUT_MEMBERTAGS])->getValue([GD_URE_Module_Processor_ProfileMultiSelectFormInputs::class, GD_URE_Module_Processor_ProfileMultiSelectFormInputs::COMPONENT_URE_FORMINPUT_MEMBERTAGS]);
        
        $withArgumentsAST->addArgument(new \PoP\GraphQLParser\Spec\Parser\Ast\Argument('community', $community, \PoP\GraphQLParser\StaticHelpers\LocationHelper::getNonSpecificLocation()));
        $withArgumentsAST->addArgument(new \PoP\GraphQLParser\Spec\Parser\Ast\Argument('user_id', App::query(InputNames::USER_ID), \PoP\GraphQLParser\StaticHelpers\LocationHelper::getNonSpecificLocation()));
        // $withArgumentsAST->addArgument(new \PoP\GraphQLParser\Spec\Parser\Ast\Argument('nonce', App::query(POP_INPUTNAME_NONCE), \PoP\GraphQLParser\StaticHelpers\LocationHelper::getNonSpecificLocation()));
        $withArgumentsAST->addArgument(new \PoP\GraphQLParser\Spec\Parser\Ast\Argument('status', trim($this->getComponentProcessorManager()->getComponentProcessor([GD_URE_Module_Processor_SelectFormInputs::class, GD_URE_Module_Processor_SelectFormInputs::COMPONENT_URE_FORMINPUT_MEMBERSTATUS])->getValue([GD_URE_Module_Processor_SelectFormInputs::class, GD_URE_Module_Processor_SelectFormInputs::COMPONENT_URE_FORMINPUT_MEMBERSTATUS])), \PoP\GraphQLParser\StaticHelpers\LocationHelper::getNonSpecificLocation()));
        $withArgumentsAST->addArgument(new \PoP\GraphQLParser\Spec\Parser\Ast\Argument('privileges', $privileges ?? array(), \PoP\GraphQLParser\StaticHelpers\LocationHelper::getNonSpecificLocation()));
        $withArgumentsAST->addArgument(new \PoP\GraphQLParser\Spec\Parser\Ast\Argument('tags', $tags ?? array(), \PoP\GraphQLParser\StaticHelpers\LocationHelper::getNonSpecificLocation()));
    }
}
