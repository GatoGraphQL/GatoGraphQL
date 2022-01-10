<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolverBridges;

use PoP\ComponentModel\MutationResolverBridges\AbstractComponentMutationResolverBridge;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\State\ApplicationState;
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

    public function getFormData(): array
    {
        $vars = ApplicationState::getVars();
        $user_id = $vars['is-user-logged-in'] ? $vars['current-user-id'] : '';
        $inputs = MutationResolverUtils::getMyCommunityFormInputs();
        $communities = $this->getModuleProcessorManager()->getProcessor($inputs['communities'])->getValue($inputs['communities']);
        $form_data = array(
            'user_id' => $user_id,
            'communities' => $communities ?? array(),
        );

        // Allow to add extra inputs
        $form_data = $this->getHooksAPI()->applyFilters('gd_createupdate_mycommunities:form_data', $form_data);

        return $form_data;
    }
}
