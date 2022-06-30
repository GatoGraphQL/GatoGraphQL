<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolverBridges;

use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\StaticHelpers\LocationHelper;
use GD_URE_Module_Processor_ProfileMultiSelectFormInputs;
use GD_URE_Module_Processor_SelectFormInputs;
use PoP\ComponentModel\MutationResolverBridges\AbstractComponentMutationResolverBridge;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\InputList;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Literal;
use PoP\Root\App;
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

    public function addArgumentsForMutation(\PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface $mutationField): void
    {
        $community = App::getState('is-user-logged-in') ? App::getState('current-user-id') : '';
        $privileges = $this->getComponentProcessorManager()->getComponentProcessor([GD_URE_Module_Processor_ProfileMultiSelectFormInputs::class, GD_URE_Module_Processor_ProfileMultiSelectFormInputs::COMPONENT_URE_FORMINPUT_MEMBERPRIVILEGES])->getValue([GD_URE_Module_Processor_ProfileMultiSelectFormInputs::class, GD_URE_Module_Processor_ProfileMultiSelectFormInputs::COMPONENT_URE_FORMINPUT_MEMBERPRIVILEGES]);
        $tags = $this->getComponentProcessorManager()->getComponentProcessor([GD_URE_Module_Processor_ProfileMultiSelectFormInputs::class, GD_URE_Module_Processor_ProfileMultiSelectFormInputs::COMPONENT_URE_FORMINPUT_MEMBERTAGS])->getValue([GD_URE_Module_Processor_ProfileMultiSelectFormInputs::class, GD_URE_Module_Processor_ProfileMultiSelectFormInputs::COMPONENT_URE_FORMINPUT_MEMBERTAGS]);
        
        $mutationField->addArgument(new Argument('community', new Literal($community, LocationHelper::getNonSpecificLocation()), LocationHelper::getNonSpecificLocation()));
        $mutationField->addArgument(new Argument('user_id', new Literal(App::query(InputNames::USER_ID), LocationHelper::getNonSpecificLocation()), LocationHelper::getNonSpecificLocation()));
        // $mutationField->addArgument(new \PoP\GraphQLParser\Spec\Parser\Ast\Argument('nonce', new Literal(App::query(POP_INPUTNAME_NONCE), \PoP\GraphQLParser\StaticHelpers\LocationHelper::getNonSpecificLocation()), \PoP\GraphQLParser\StaticHelpers\LocationHelper::getNonSpecificLocation()));
        $mutationField->addArgument(new Argument('status', new Literal(trim($this->getComponentProcessorManager()->getComponentProcessor([GD_URE_Module_Processor_SelectFormInputs::class, GD_URE_Module_Processor_SelectFormInputs::COMPONENT_URE_FORMINPUT_MEMBERSTATUS])->getValue([GD_URE_Module_Processor_SelectFormInputs::class, GD_URE_Module_Processor_SelectFormInputs::COMPONENT_URE_FORMINPUT_MEMBERSTATUS])), LocationHelper::getNonSpecificLocation()), LocationHelper::getNonSpecificLocation()));
        $mutationField->addArgument(new Argument('privileges', new InputList($privileges ?? array(), LocationHelper::getNonSpecificLocation()), LocationHelper::getNonSpecificLocation()));
        $mutationField->addArgument(new Argument('tags', new InputList($tags ?? array(), LocationHelper::getNonSpecificLocation()), LocationHelper::getNonSpecificLocation()));
    }
}
