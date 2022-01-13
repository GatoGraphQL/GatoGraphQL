<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolverBridges;

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

    public function getFormData(): array
    {
        $user_id = App::getState('is-user-logged-in') ? App::getState('current-user-id') : '';
        $inputs = MutationResolverUtils::getMyCommunityFormInputs();
        $communities = $this->getModuleProcessorManager()->getProcessor($inputs['communities'])->getValue($inputs['communities']);
        $form_data = array(
            'user_id' => $user_id,
            'communities' => $communities ?? array(),
        );

        // Allow to add extra inputs
        $form_data = App::applyFilters('gd_createupdate_mycommunities:form_data', $form_data);

        return $form_data;
    }
}
