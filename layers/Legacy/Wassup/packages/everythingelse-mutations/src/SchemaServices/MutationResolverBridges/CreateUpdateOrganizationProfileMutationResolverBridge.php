<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolverBridges;

use Exception;
use PoP\Application\HelperAPIFactory;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\InputList;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Literal;
use PoP\GraphQLParser\StaticHelpers\LocationHelper;
use PoP\Root\App;
use PoP\Root\Exception\GenericSystemException;
use PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolvers\CreateUpdateOrganizationProfileMutationResolver;

class CreateUpdateOrganizationProfileMutationResolverBridge extends CreateUpdateProfileMutationResolverBridge
{
    use CreateUpdateProfileMutationResolverBridgeTrait;
    
    private ?CreateUpdateOrganizationProfileMutationResolver $createUpdateOrganizationProfileMutationResolver = null;
    
    final public function setCreateUpdateOrganizationProfileMutationResolver(CreateUpdateOrganizationProfileMutationResolver $createUpdateOrganizationProfileMutationResolver): void
    {
        $this->createUpdateOrganizationProfileMutationResolver = $createUpdateOrganizationProfileMutationResolver;
    }
    final protected function getCreateUpdateOrganizationProfileMutationResolver(): CreateUpdateOrganizationProfileMutationResolver
    {
        return $this->createUpdateOrganizationProfileMutationResolver ??= $this->instanceManager->getInstance(CreateUpdateOrganizationProfileMutationResolver::class);
    }

    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->getCreateUpdateOrganizationProfileMutationResolver();
    }

    private function getFormInputs()
    {
        return $this->getCommonuserrolesFormInputs();
    }
    protected function getCommonuserrolesFormInputs()
    {
        $inputs = App::applyFilters(
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
            throw new GenericSystemException(
                sprintf(
                    'No form inputs defined for: %s',
                    '"' . implode('", "', array_keys($null_inputs)) . '"'
                )
            );
        }

        return $inputs;
    }

    public function addArgumentsForMutation(\PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface $mutationField): void
    {
        $this->getCommonuserrolesFormData($mutationField);
        $this->getUsercommunitiesFormData($mutationField);
    }
    protected function getCommonuserrolesFormData(\PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface $mutationField)
    {
        $cmsapplicationhelpers = HelperAPIFactory::getInstance();
        $inputs = $this->getFormInputs();
        $organizationtypes = $this->getComponentProcessorManager()->getComponentProcessor($inputs['organizationtypes'])->getValue($inputs['organizationtypes']);
        $organizationcategories = $this->getComponentProcessorManager()->getComponentProcessor($inputs['organizationcategories'])->getValue($inputs['organizationcategories']);
        $mutationField->addArgument(new Argument('organizationtypes', new InputList($organizationtypes ?? array(), LocationHelper::getNonSpecificLocation()), LocationHelper::getNonSpecificLocation()));
        $mutationField->addArgument(new Argument('organizationcategories', new InputList($organizationcategories ?? array(), LocationHelper::getNonSpecificLocation()), LocationHelper::getNonSpecificLocation()));
        $mutationField->addArgument(new Argument('contact_number', new Literal(trim($cmsapplicationhelpers->escapeAttributes($this->getComponentProcessorManager()->getComponentProcessor($inputs['contact_number'])->getValue($inputs['contact_number']))), LocationHelper::getNonSpecificLocation()), LocationHelper::getNonSpecificLocation()));
        $mutationField->addArgument(new Argument('contact_person', new Literal(trim($cmsapplicationhelpers->escapeAttributes($this->getComponentProcessorManager()->getComponentProcessor($inputs['contact_person'])->getValue($inputs['contact_person']))), LocationHelper::getNonSpecificLocation()), LocationHelper::getNonSpecificLocation()));
    }
}
