<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolverBridges;

use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\State\ApplicationState;
use PoP\ComponentModel\MutationResolverBridges\AbstractComponentMutationResolverBridge;
use PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolvers\UpdateUserAvatarMutationResolver;

class UpdateUserAvatarMutationResolverBridge extends AbstractComponentMutationResolverBridge
{
    public function __construct(
        protected \PoP\Hooks\HooksAPIInterface $hooksAPI,
        protected \PoP\Translation\TranslationAPIInterface $translationAPI,
        protected \PoP\ComponentModel\Instances\InstanceManagerInterface $instanceManager,
        protected \PoP\ComponentModel\MutationResolution\MutationResolutionManagerInterface $mutationResolutionManager,
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
        return $this->UpdateUserAvatarMutationResolver;
        protected UpdateUserAvatarMutationResolver $UpdateUserAvatarMutationResolver,
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
