<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolverBridges;

use PoP\GraphQLParser\Spec\Parser\Ast\WithArgumentsInterface;
use PoP\Root\App;
use PoP\ComponentModel\MutationResolverBridges\AbstractComponentMutationResolverBridge;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolvers\UpdateUserAvatarMutationResolver;

class UpdateUserAvatarMutationResolverBridge extends AbstractComponentMutationResolverBridge
{
    private ?UpdateUserAvatarMutationResolver $updateUserAvatarMutationResolver = null;
    
    final public function setUpdateUserAvatarMutationResolver(UpdateUserAvatarMutationResolver $updateUserAvatarMutationResolver): void
    {
        $this->updateUserAvatarMutationResolver = $updateUserAvatarMutationResolver;
    }
    final protected function getUpdateUserAvatarMutationResolver(): UpdateUserAvatarMutationResolver
    {
        return $this->updateUserAvatarMutationResolver ??= $this->instanceManager->getInstance(UpdateUserAvatarMutationResolver::class);
    }
    
    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->getUpdateUserAvatarMutationResolver();
    }

    public function addArgumentsForMutation(WithArgumentsInterface $withArgumentsAST): void
    {
        $user_id = App::getState('is-user-logged-in') ? App::getState('current-user-id') : '';
        $withArgumentsAST->addArgument(new \PoP\GraphQLParser\Spec\Parser\Ast\Argument('user_id', $user_id, \PoP\GraphQLParser\StaticHelpers\LocationHelper::getNonSpecificLocation()));
    }
}
