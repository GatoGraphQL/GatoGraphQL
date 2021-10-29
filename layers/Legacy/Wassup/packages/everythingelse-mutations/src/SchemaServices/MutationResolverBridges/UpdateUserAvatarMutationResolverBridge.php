<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolverBridges;

use PoP\ComponentModel\MutationResolverBridges\AbstractComponentMutationResolverBridge;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\State\ApplicationState;
use PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolvers\UpdateUserAvatarMutationResolver;
use Symfony\Contracts\Service\Attribute\Required;

class UpdateUserAvatarMutationResolverBridge extends AbstractComponentMutationResolverBridge
{
    protected ?UpdateUserAvatarMutationResolver $updateUserAvatarMutationResolver = null;
    
    public function setUpdateUserAvatarMutationResolver(UpdateUserAvatarMutationResolver $updateUserAvatarMutationResolver): void
    {
        $this->updateUserAvatarMutationResolver = $updateUserAvatarMutationResolver;
    }
    protected function getUpdateUserAvatarMutationResolver(): UpdateUserAvatarMutationResolver
    {
        return $this->updateUserAvatarMutationResolver ??= $this->getInstanceManager()->getInstance(UpdateUserAvatarMutationResolver::class);
    }

    //#[Required]
    final public function autowireUpdateUserAvatarMutationResolverBridge(
        UpdateUserAvatarMutationResolver $updateUserAvatarMutationResolver,
    ): void {
        $this->updateUserAvatarMutationResolver = $updateUserAvatarMutationResolver;
    }
    
    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->getUpdateUserAvatarMutationResolver();
    }

    public function getFormData(): array
    {
        $vars = ApplicationState::getVars();
        $user_id = $vars['global-userstate']['is-user-logged-in'] ? $vars['global-userstate']['current-user-id'] : '';
        $form_data = array(
            'user_id' => $user_id,
        );

        return $form_data;
    }
}
