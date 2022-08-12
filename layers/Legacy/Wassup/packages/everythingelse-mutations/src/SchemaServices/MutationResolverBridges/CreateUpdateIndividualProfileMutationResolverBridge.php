<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolverBridges;

use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\Application\HelperAPIFactory;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\InputList;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Literal;
use PoP\GraphQLParser\ASTNodes\ASTNodesFactory;
use PoP\Root\App;
use PoP\Root\Exception\GenericSystemException;
use PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolvers\CreateUpdateIndividualProfileMutationResolver;

class CreateUpdateIndividualProfileMutationResolverBridge extends CreateUpdateProfileMutationResolverBridge
{
    use CreateUpdateProfileMutationResolverBridgeTrait;
    
    private ?CreateUpdateIndividualProfileMutationResolver $createUpdateIndividualProfileMutationResolver = null;
    
    final public function setCreateUpdateIndividualProfileMutationResolver(CreateUpdateIndividualProfileMutationResolver $createUpdateIndividualProfileMutationResolver): void
    {
        $this->createUpdateIndividualProfileMutationResolver = $createUpdateIndividualProfileMutationResolver;
    }
    final protected function getCreateUpdateIndividualProfileMutationResolver(): CreateUpdateIndividualProfileMutationResolver
    {
        return $this->createUpdateIndividualProfileMutationResolver ??= $this->instanceManager->getInstance(CreateUpdateIndividualProfileMutationResolver::class);
    }

    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->getCreateUpdateIndividualProfileMutationResolver();
    }

    private function getFormInputs()
    {
        $inputs = App::applyFilters(
            'GD_CreateUpdate_ProfileIndividual_Trait:form-inputs',
            array(
                'last_name' => null,
                'individualinterests' => null,
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
        $this->getCommonuserrolesFormData($mutationData);
        $this->getUsercommunitiesFormData($mutationData);
    }
    /**
     * @param array<string,mixed> $mutationData
     */
    protected function getCommonuserrolesFormData(array &$mutationData): void
    {
        $cmsapplicationhelpers = HelperAPIFactory::getInstance();
        $inputs = $this->getFormInputs();
        $individualinterests = $this->getComponentProcessorManager()->getComponentProcessor($inputs['individualinterests'])->getValue($inputs['individualinterests']);
        $mutationData['last_name'] = trim($cmsapplicationhelpers->escapeAttributes($this->getComponentProcessorManager()->getComponentProcessor($inputs['last_name'])->getValue($inputs['last_name'])));
        $mutationData['individualinterests'] = $individualinterests ?? array();
    }
}
