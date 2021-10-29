<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolverBridges;

use Exception;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolvers\CreateUpdateWithCommunityOrganizationProfileMutationResolver;
use Symfony\Contracts\Service\Attribute\Required;

class CreateUpdateWithCommunityOrganizationProfileMutationResolverBridge extends CreateUpdateOrganizationProfileMutationResolverBridge
{
    private ?CreateUpdateWithCommunityOrganizationProfileMutationResolver $createUpdateWithCommunityOrganizationProfileMutationResolver = null;
    
    public function setCreateUpdateWithCommunityOrganizationProfileMutationResolver(CreateUpdateWithCommunityOrganizationProfileMutationResolver $createUpdateWithCommunityOrganizationProfileMutationResolver): void
    {
        $this->createUpdateWithCommunityOrganizationProfileMutationResolver = $createUpdateWithCommunityOrganizationProfileMutationResolver;
    }
    protected function getCreateUpdateWithCommunityOrganizationProfileMutationResolver(): CreateUpdateWithCommunityOrganizationProfileMutationResolver
    {
        return $this->createUpdateWithCommunityOrganizationProfileMutationResolver ??= $this->getInstanceManager()->getInstance(CreateUpdateWithCommunityOrganizationProfileMutationResolver::class);
    }
    
    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->getCreateUpdateWithCommunityOrganizationProfileMutationResolver();
    }

    private function getFormInputs()
    {
        return array_merge(
            $this->getCommonuserrolesFormInputs(),
            $this->getProfileorganizationFormInputs()
        );
    }
    protected function getProfileorganizationFormInputs()
    {
        $inputs = $this->getHooksAPI()->applyFilters(
            'GD_CommonUserRole_UserCommunities_CreateUpdate_ProfileOrganization:form-inputs',
            array(
                'is_community' => null,
            )
        );

        // If any input is null, throw an exception
        $null_inputs = array_filter($inputs, 'is_null');
        if ($null_inputs) {
            throw new Exception(
                sprintf(
                    'No form inputs defined for: %s',
                    '"' . implode('", "', array_keys($null_inputs)) . '"'
                )
            );
        }

        return $inputs;
    }

    public function getFormData(): array
    {
        $inputs = $this->getFormInputs();
        return array_merge(
            parent::getFormData(),
            $this->getCommonuserrolesFormData(),
            array(
                'is_community' => (bool)$this->getModuleProcessorManager()->getProcessor($inputs['is_community'])->getValue($inputs['is_community']),
            )
        );
    }
}
