<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolverBridges;

use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\Root\Exception\GenericSystemException;
use PoP\Root\App;
use Exception;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolvers\CreateUpdateWithCommunityOrganizationProfileMutationResolver;

class CreateUpdateWithCommunityOrganizationProfileMutationResolverBridge extends CreateUpdateOrganizationProfileMutationResolverBridge
{
    private ?CreateUpdateWithCommunityOrganizationProfileMutationResolver $createUpdateWithCommunityOrganizationProfileMutationResolver = null;
    
    final public function setCreateUpdateWithCommunityOrganizationProfileMutationResolver(CreateUpdateWithCommunityOrganizationProfileMutationResolver $createUpdateWithCommunityOrganizationProfileMutationResolver): void
    {
        $this->createUpdateWithCommunityOrganizationProfileMutationResolver = $createUpdateWithCommunityOrganizationProfileMutationResolver;
    }
    final protected function getCreateUpdateWithCommunityOrganizationProfileMutationResolver(): CreateUpdateWithCommunityOrganizationProfileMutationResolver
    {
        if ($this->createUpdateWithCommunityOrganizationProfileMutationResolver === null) {
            /** @var CreateUpdateWithCommunityOrganizationProfileMutationResolver */
            $createUpdateWithCommunityOrganizationProfileMutationResolver = $this->instanceManager->getInstance(CreateUpdateWithCommunityOrganizationProfileMutationResolver::class);
            $this->createUpdateWithCommunityOrganizationProfileMutationResolver = $createUpdateWithCommunityOrganizationProfileMutationResolver;
        }
        return $this->createUpdateWithCommunityOrganizationProfileMutationResolver;
    }
    
    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->getCreateUpdateWithCommunityOrganizationProfileMutationResolver();
    }

    /**
     * @return mixed[]
     */
    private function getFormInputs(): array
    {
        return array_merge(
            $this->getCommonuserrolesFormInputs(),
            $this->getProfileorganizationFormInputs()
        );
    }
    protected function getProfileorganizationFormInputs()
    {
        $inputs = App::applyFilters(
            'GD_CommonUserRole_UserCommunities_CreateUpdate_ProfileOrganization:form-inputs',
            array(
                'is_community' => null,
            )
        );

        // If any input is null, throw an exception
        $null_inputs = array_filter($inputs, 'is_null');
        if ($null_inputs) {
            throw new GenericSystemException(
                sprintf(
                    'No form inputs defined for: %s',
                    '"' . implode('", "', array_keys($null_inputs)) . '"'
                )
            );
        }

        return $inputs;
    }

    /**
     * @param array<string,mixed> $mutationData
     */
    public function addMutationDataForFieldDataAccessor(array &$mutationData): void
    {
        $inputs = $this->getFormInputs();
        return array_merge(
            parent::getFormData(),
            $this->getCommonuserrolesFormData(),
            array(
                'is_community' => (bool)$this->getComponentProcessorManager()->getComponentProcessor($inputs['is_community'])->getValue($inputs['is_community']),
            )
        );
    }
}
