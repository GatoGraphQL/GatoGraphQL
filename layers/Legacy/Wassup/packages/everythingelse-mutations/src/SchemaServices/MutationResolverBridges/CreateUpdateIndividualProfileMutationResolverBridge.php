<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolverBridges;

use PoP\Application\HelperAPIFactory;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\InputList;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Literal;
use PoP\GraphQLParser\Spec\Parser\Ast\WithArgumentsInterface;
use PoP\GraphQLParser\StaticHelpers\LocationHelper;
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

    public function addArgumentsForMutation(WithArgumentsInterface $withArgumentsAST): void
    {
        $this->getCommonuserrolesFormData($withArgumentsAST);
        $this->getUsercommunitiesFormData($withArgumentsAST);
    }
    protected function getCommonuserrolesFormData(WithArgumentsInterface $withArgumentsAST)
    {
        $cmsapplicationhelpers = HelperAPIFactory::getInstance();
        $inputs = $this->getFormInputs();
        $individualinterests = $this->getComponentProcessorManager()->getComponentProcessor($inputs['individualinterests'])->getValue($inputs['individualinterests']);
        $withArgumentsAST->addArgument(new Argument('last_name', new Literal(trim($cmsapplicationhelpers->escapeAttributes($this->getComponentProcessorManager()->getComponentProcessor($inputs['last_name'])->getValue($inputs['last_name']))), LocationHelper::getNonSpecificLocation()), LocationHelper::getNonSpecificLocation()));
        $withArgumentsAST->addArgument(new Argument('individualinterests', new InputList($individualinterests ?? array(), LocationHelper::getNonSpecificLocation()), LocationHelper::getNonSpecificLocation()));
    }
}
