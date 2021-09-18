<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolverBridges;

use Exception;
use PoP\Application\HelperAPIFactory;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolverBridges\CreateUpdateProfileMutationResolverBridgeTrait;
use PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolvers\CreateUpdateIndividualProfileMutationResolver;
use PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolvers\CreateUpdateProfileMutationResolver;

class CreateUpdateIndividualProfileMutationResolverBridge extends CreateUpdateProfileMutationResolverBridge
{
    use CreateUpdateProfileMutationResolverBridgeTrait;

    public function __construct(
        \PoP\Hooks\HooksAPIInterface $hooksAPI,
        \PoP\Translation\TranslationAPIInterface $translationAPI,
        \PoP\ComponentModel\Instances\InstanceManagerInterface $instanceManager,
        \PoP\ComponentModel\MutationResolution\MutationResolutionManagerInterface $mutationResolutionManager,
        CreateUpdateProfileMutationResolver $createUpdateProfileMutationResolver,
        protected CreateUpdateIndividualProfileMutationResolver $createUpdateIndividualProfileMutationResolver,
    ) {
        parent::__construct(
            $hooksAPI,
            $translationAPI,
            $instanceManager,
            $mutationResolutionManager,
            $createUpdateProfileMutationResolver
        );
    }

    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->createUpdateIndividualProfileMutationResolver;
    }

    private function getFormInputs()
    {
        $inputs = $this->hooksAPI->applyFilters(
            'GD_CreateUpdate_ProfileIndividual_Trait:form-inputs',
            array(
                'last_name' => null,
                'individualinterests' => null,
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
        $individualinterests = $this->moduleProcessorManager->getProcessor($inputs['individualinterests'])->getValue($inputs['individualinterests']);
        return array(
            'last_name' => trim($cmsapplicationhelpers->escapeAttributes($this->moduleProcessorManager->getProcessor($inputs['last_name'])->getValue($inputs['last_name']))),
            'individualinterests' => $individualinterests ?? array(),
        );
    }
}
