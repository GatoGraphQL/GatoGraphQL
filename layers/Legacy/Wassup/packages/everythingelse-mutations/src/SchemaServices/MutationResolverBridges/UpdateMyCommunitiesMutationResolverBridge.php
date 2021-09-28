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
use PoPSitesWassup\EverythingElseMutations\MutationResolverUtils\MutationResolverUtils;
use PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolvers\UpdateMyCommunitiesMutationResolver;

class UpdateMyCommunitiesMutationResolverBridge extends AbstractComponentMutationResolverBridge
{
    protected UpdateMyCommunitiesMutationResolver $updateMyCommunitiesMutationResolver;
    public function __construct(
        UpdateMyCommunitiesMutationResolver $updateMyCommunitiesMutationResolver,
    ) {
        $this->updateMyCommunitiesMutationResolver = $updateMyCommunitiesMutationResolver;
        }
    
    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->updateMyCommunitiesMutationResolver;
    }

    public function getFormData(): array
    {
        $vars = ApplicationState::getVars();
        $user_id = $vars['global-userstate']['is-user-logged-in'] ? $vars['global-userstate']['current-user-id'] : '';
        $inputs = MutationResolverUtils::getMyCommunityFormInputs();
        $communities = $this->moduleProcessorManager->getProcessor($inputs['communities'])->getValue($inputs['communities']);
        $form_data = array(
            'user_id' => $user_id,
            'communities' => $communities ?? array(),
        );

        // Allow to add extra inputs
        $form_data = $this->hooksAPI->applyFilters('gd_createupdate_mycommunities:form_data', $form_data);

        return $form_data;
    }
}
