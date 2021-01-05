<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\MutationResolverBridges;

use PoPSitesWassup\EverythingElseMutations\MutationResolvers\CreateUpdateWithCommunityOrganizationProfileMutationResolver;
use PoPSitesWassup\EverythingElseMutations\MutationResolvers\CreateUpdateOrganizationProfileMutationResolver;

class CreateUpdateOrganizationProfileMutationResolverBridge extends CreateUpdateProfileMutationResolverBridge
{
    use CreateUpdateProfileMutationResolverBridgeTrait;

    public function getMutationResolverClass(): string
    {
        return CreateUpdateOrganizationProfileMutationResolver::class;
    }

    private function getFormInputs()
    {
        return $this->getCommonuserrolesFormInputs();
    }
    protected function getCommonuserrolesFormInputs()
    {
        $inputs = HooksAPIFacade::getInstance()->applyFilters(
            'GD_CreateUpdate_ProfileOrganization_Trait:form-inputs',
            array(
                'organizationtypes' => null,
                'organizationcategories' => null,
                'contact_number' => null,
                'contact_person' => null,
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
        return array_merge(
            parent::getFormData(),
            $this->getCommonuserrolesFormData(),
            $this->getUsercommunitiesFormData()
        );
    }
    protected function getCommonuserrolesFormData()
    {
        $cmsapplicationhelpers = \PoP\Application\HelperAPIFactory::getInstance();
        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();
        $inputs = $this->getFormInputs();
        $organizationtypes = $moduleprocessor_manager->getProcessor($inputs['organizationtypes'])->getValue($inputs['organizationtypes']);
        $organizationcategories = $moduleprocessor_manager->getProcessor($inputs['organizationcategories'])->getValue($inputs['organizationcategories']);
        return array(
            'organizationtypes' => $organizationtypes ?? array(),
            'organizationcategories' => $organizationcategories ?? array(),
            'contact_number' => trim($cmsapplicationhelpers->escapeAttributes($moduleprocessor_manager->getProcessor($inputs['contact_number'])->getValue($inputs['contact_number']))),
            'contact_person' => trim($cmsapplicationhelpers->escapeAttributes($moduleprocessor_manager->getProcessor($inputs['contact_person'])->getValue($inputs['contact_person']))),
        );
    }
}
