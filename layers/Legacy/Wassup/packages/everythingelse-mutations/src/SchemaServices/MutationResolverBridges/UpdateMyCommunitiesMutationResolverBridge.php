<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolverBridges;

use PoP\GraphQLParser\Spec\Parser\Ast\WithArgumentsInterface;
use PoP\Root\App;
use PoP\ComponentModel\MutationResolverBridges\AbstractComponentMutationResolverBridge;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
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

    public function addArgumentsForMutation(WithArgumentsInterface $withArgumentsAST): void
    {
        $user_id = App::getState('is-user-logged-in') ? App::getState('current-user-id') : '';
        $inputs = MutationResolverUtils::getMyCommunityFormInputs();
        $communities = $this->getComponentProcessorManager()->getComponentProcessor($inputs['communities'])->getValue($inputs['communities']);
        
        $withArgumentsAST->addArgument(new \PoP\GraphQLParser\Spec\Parser\Ast\Argument('user_id', $user_id, \PoP\GraphQLParser\StaticHelpers\LocationHelper::getNonSpecificLocation()));
        $withArgumentsAST->addArgument(new \PoP\GraphQLParser\Spec\Parser\Ast\Argument('communities', $communities ?? array(), \PoP\GraphQLParser\StaticHelpers\LocationHelper::getNonSpecificLocation()));

        // Allow to add extra inputs
        App::doAction('gd_createupdate_mycommunities:form_data', $withArgumentsAST);
    }
}
