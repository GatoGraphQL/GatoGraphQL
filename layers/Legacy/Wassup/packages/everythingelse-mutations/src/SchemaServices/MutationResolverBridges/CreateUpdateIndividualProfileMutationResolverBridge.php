<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolverBridges;

use Exception;
use PoP\Application\HelperAPIFactory;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolvers\CreateUpdateIndividualProfileMutationResolver;
use Symfony\Contracts\Service\Attribute\Required;

class CreateUpdateIndividualProfileMutationResolverBridge extends CreateUpdateProfileMutationResolverBridge
{
    use CreateUpdateProfileMutationResolverBridgeTrait;
    
    protected CreateUpdateIndividualProfileMutationResolver $createUpdateIndividualProfileMutationResolver;
    
    #[Required]
    final public function autowireCreateUpdateIndividualProfileMutationResolverBridge(
        CreateUpdateIndividualProfileMutationResolver $createUpdateIndividualProfileMutationResolver,
    ): void {
        $this->createUpdateIndividualProfileMutationResolver = $createUpdateIndividualProfileMutationResolver;
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
