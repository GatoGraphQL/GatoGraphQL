<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolverBridges;

use PoP\Hooks\HooksAPIInterface;
use PoP\Translation\TranslationAPIInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\MutationResolution\MutationResolutionManagerInterface;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\State\ApplicationState;
use PoP\ComponentModel\MutationResolverBridges\AbstractComponentMutationResolverBridge;
use PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolvers\UpdateUserAvatarMutationResolver;

class UpdateUserAvatarMutationResolverBridge extends AbstractComponentMutationResolverBridge
{
    public function __construct(
        HooksAPIInterface $hooksAPI,
        TranslationAPIInterface $translationAPI,
        InstanceManagerInterface $instanceManager,
        MutationResolutionManagerInterface $mutationResolutionManager,
        protected UpdateUserAvatarMutationResolver $updateUserAvatarMutationResolver,
    ) {
        parent::__construct(
            $hooksAPI,
            $translationAPI,
            $instanceManager,
            $mutationResolutionManager,
        );
    }
    
    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->updateUserAvatarMutationResolver;
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
