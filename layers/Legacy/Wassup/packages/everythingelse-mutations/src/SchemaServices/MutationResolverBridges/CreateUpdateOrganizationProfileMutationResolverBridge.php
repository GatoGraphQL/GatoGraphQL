<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolverBridges;

use Exception;
use PoP\Application\HelperAPIFactory;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolverBridges\CreateUpdateProfileMutationResolverBridgeTrait;
use PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolvers\CreateUpdateOrganizationProfileMutationResolver;
use PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolvers\CreateUpdateProfileMutationResolver;

class CreateUpdateOrganizationProfileMutationResolverBridge extends CreateUpdateProfileMutationResolverBridge
{
    use CreateUpdateProfileMutationResolverBridgeTrait;

    public function __construct(
        \PoP\Hooks\HooksAPIInterface $hooksAPI,
        \PoP\Translation\TranslationAPIInterface $translationAPI,
        \PoP\ComponentModel\Instances\InstanceManagerInterface $instanceManager,
        \PoP\ComponentModel\MutationResolution\MutationResolutionManagerInterface $mutationResolutionManager,
        CreateUpdateProfileMutationResolver $createUpdateProfileMutationResolver,
        protected CreateUpdateOrganizationProfileMutationResolver $createUpdateOrganizationProfileMutationResolver,
    ) {
        parent::__construct(
            $hooksAPI,
            $translationAPI,
            $instanceManager,
            $mutationResolutionManager,
            $createUpdateProfileMutationResolver,
        );
    }

    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->createUpdateOrganizationProfileMutationResolver;
    }

    private function getFormInputs()
    {
        return $this->getCommonuserrolesFormInputs();
    }
    protected function getCommonuserrolesFormInputs()
    {
        $inputs = $this->hooksAPI->applyFilters(
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
        $cmsapplicationhelpers = HelperAPIFactory::getInstance();
        $inputs = $this->getFormInputs();
        $organizationtypes = $this->moduleProcessorManager->getProcessor($inputs['organizationtypes'])->getValue($inputs['organizationtypes']);
        $organizationcategories = $this->moduleProcessorManager->getProcessor($inputs['organizationcategories'])->getValue($inputs['organizationcategories']);
        return array(
            'organizationtypes' => $organizationtypes ?? array(),
            'organizationcategories' => $organizationcategories ?? array(),
            'contact_number' => trim($cmsapplicationhelpers->escapeAttributes($this->moduleProcessorManager->getProcessor($inputs['contact_number'])->getValue($inputs['contact_number']))),
            'contact_person' => trim($cmsapplicationhelpers->escapeAttributes($this->moduleProcessorManager->getProcessor($inputs['contact_person'])->getValue($inputs['contact_person']))),
        );
    }
}
