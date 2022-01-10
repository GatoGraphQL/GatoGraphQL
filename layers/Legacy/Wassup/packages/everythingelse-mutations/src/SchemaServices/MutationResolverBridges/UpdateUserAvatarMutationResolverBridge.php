<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolverBridges;

use PoP\ComponentModel\MutationResolverBridges\AbstractComponentMutationResolverBridge;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\State\ApplicationState;
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

    public function getFormData(): array
    {
        $vars = ApplicationState::getVars();
        $user_id = $vars['is-user-logged-in'] ? $vars['current-user-id'] : '';
        $form_data = array(
            'user_id' => $user_id,
        );

        return $form_data;
    }
}
