<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolverBridges;

use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\ASTNodes\ASTNodesFactory;
use PoP\ComponentModel\MutationResolverBridges\AbstractComponentMutationResolverBridge;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\InputList;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Literal;
use PoP\Root\App;
use PoPSitesWassup\EverythingElseMutations\MutationResolverUtils\MutationResolverUtils;
use PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolvers\UpdateMyCommunitiesMutationResolver;

class UpdateMyCommunitiesMutationResolverBridge extends AbstractComponentMutationResolverBridge
{
    private ?UpdateMyCommunitiesMutationResolver $updateMyCommunitiesMutationResolver = null;
    
    final public function setUpdateMyCommunitiesMutationResolver(UpdateMyCommunitiesMutationResolver $updateMyCommunitiesMutationResolver): void
    {
        $this->updateMyCommunitiesMutationResolver = $updateMyCommunitiesMutationResolver;
    }
    final protected function getUpdateMyCommunitiesMutationResolver(): UpdateMyCommunitiesMutationResolver
    {
        return $this->updateMyCommunitiesMutationResolver ??= $this->instanceManager->getInstance(UpdateMyCommunitiesMutationResolver::class);
    }
    
    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->getUpdateMyCommunitiesMutationResolver();
    }

    /**
     * @param array<string,mixed> $mutationData
     */
    public function addMutationDataForFieldDataAccessor(array &$mutationData): void
    {
        $user_id = App::getState('is-user-logged-in') ? App::getState('current-user-id') : '';
        $inputs = MutationResolverUtils::getMyCommunityFormInputs();
        $communities = $this->getComponentProcessorManager()->getComponentProcessor($inputs['communities'])->getValue($inputs['communities']);
        
        $mutationData['user_id'] = $user_id;
        $mutationData['communities'] = $communities ?? array();

        // Allow to add extra inputs
        $mutationDataInArray = [&$mutationData];
        App::doAction('gd_createupdate_mycommunities:form_data', $mutationDataInArray);
    }
}
