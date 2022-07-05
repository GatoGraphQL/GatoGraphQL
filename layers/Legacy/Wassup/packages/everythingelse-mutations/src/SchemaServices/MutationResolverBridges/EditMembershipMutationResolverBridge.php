<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolverBridges;

use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
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

    /**
     * @param array<string,mixed> $mutationData
     */
    public function addMutationDataForFieldDataAccessor(array &$mutationData): void
    {
        $community = App::getState('is-user-logged-in') ? App::getState('current-user-id') : '';
        $privileges = $this->getComponentProcessorManager()->getComponentProcessor([GD_URE_Module_Processor_ProfileMultiSelectFormInputs::class, GD_URE_Module_Processor_ProfileMultiSelectFormInputs::COMPONENT_URE_FORMINPUT_MEMBERPRIVILEGES])->getValue([GD_URE_Module_Processor_ProfileMultiSelectFormInputs::class, GD_URE_Module_Processor_ProfileMultiSelectFormInputs::COMPONENT_URE_FORMINPUT_MEMBERPRIVILEGES]);
        $tags = $this->getComponentProcessorManager()->getComponentProcessor([GD_URE_Module_Processor_ProfileMultiSelectFormInputs::class, GD_URE_Module_Processor_ProfileMultiSelectFormInputs::COMPONENT_URE_FORMINPUT_MEMBERTAGS])->getValue([GD_URE_Module_Processor_ProfileMultiSelectFormInputs::class, GD_URE_Module_Processor_ProfileMultiSelectFormInputs::COMPONENT_URE_FORMINPUT_MEMBERTAGS]);
        
        $mutationData['community'] = $community;
        $mutationData['user_id'] = App::query(InputNames::USER_ID);
        // $mutationData['nonce'] = App::query(POP_INPUTNAME_NONCE);
        $mutationData['status'] = trim($this->getComponentProcessorManager()->getComponentProcessor([GD_URE_Module_Processor_SelectFormInputs::class, GD_URE_Module_Processor_SelectFormInputs::COMPONENT_URE_FORMINPUT_MEMBERSTATUS])->getValue([GD_URE_Module_Processor_SelectFormInputs::class, GD_URE_Module_Processor_SelectFormInputs::COMPONENT_URE_FORMINPUT_MEMBERSTATUS]));
        $mutationData['privileges'] = $privileges ?? array();
        $mutationData['tags'] = $tags ?? array();
    }
}
