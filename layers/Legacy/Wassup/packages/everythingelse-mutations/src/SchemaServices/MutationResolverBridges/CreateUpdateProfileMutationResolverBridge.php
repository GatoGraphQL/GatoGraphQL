<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolverBridges;

use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\StaticHelpers\LocationHelper;
use Exception;
use PoP\ComponentModel\MutationResolverBridges\AbstractComponentMutationResolverBridge;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Literal;
use PoP\Root\App;
use PoP\Root\Exception\GenericSystemException;
use PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolvers\CreateUpdateProfileMutationResolver;

class CreateUpdateProfileMutationResolverBridge extends AbstractComponentMutationResolverBridge
{
    private ?CreateUpdateProfileMutationResolver $createUpdateProfileMutationResolver = null;
    
    final public function setCreateUpdateProfileMutationResolver(CreateUpdateProfileMutationResolver $createUpdateProfileMutationResolver): void
    {
        $this->createUpdateProfileMutationResolver = $createUpdateProfileMutationResolver;
    }
    final protected function getCreateUpdateProfileMutationResolver(): CreateUpdateProfileMutationResolver
    {
        return $this->createUpdateProfileMutationResolver ??= $this->instanceManager->getInstance(CreateUpdateProfileMutationResolver::class);
    }
    
    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->getCreateUpdateProfileMutationResolver();
    }

    public function addArgumentsForMutation(FieldInterface $mutationField): void
    {
        parent::addArgumentsForMutation($mutationField);

        $inputs = $this->getFormInputs();
        $mutationField->addArgument(new Argument('short_description', new Literal(trim($this->getComponentProcessorManager()->getComponentProcessor($inputs['short_description'])->getValue($inputs['short_description'])), LocationHelper::getNonSpecificLocation()), LocationHelper::getNonSpecificLocation()));
        $mutationField->addArgument(new Argument('display_email', new Literal($this->getComponentProcessorManager()->getComponentProcessor($inputs['display_email'])->getValue($inputs['display_email']), LocationHelper::getNonSpecificLocation()), LocationHelper::getNonSpecificLocation()));
        $mutationField->addArgument(new Argument('facebook', new Literal(trim($this->getComponentProcessorManager()->getComponentProcessor($inputs['facebook'])->getValue($inputs['facebook'])), LocationHelper::getNonSpecificLocation()), LocationHelper::getNonSpecificLocation()));
        $mutationField->addArgument(new Argument('twitter', new Literal(trim($this->getComponentProcessorManager()->getComponentProcessor($inputs['twitter'])->getValue($inputs['twitter'])), LocationHelper::getNonSpecificLocation()), LocationHelper::getNonSpecificLocation()));
        $mutationField->addArgument(new Argument('linkedin', new Literal(trim($this->getComponentProcessorManager()->getComponentProcessor($inputs['linkedin'])->getValue($inputs['linkedin'])), LocationHelper::getNonSpecificLocation()), LocationHelper::getNonSpecificLocation()));
        $mutationField->addArgument(new Argument('youtube', new Literal(trim($this->getComponentProcessorManager()->getComponentProcessor($inputs['youtube'])->getValue($inputs['youtube'])), LocationHelper::getNonSpecificLocation()), LocationHelper::getNonSpecificLocation()));
        $mutationField->addArgument(new Argument('instagram', new Literal(trim($this->getComponentProcessorManager()->getComponentProcessor($inputs['instagram'])->getValue($inputs['instagram'])), LocationHelper::getNonSpecificLocation()), LocationHelper::getNonSpecificLocation()));
        // $mutationField->addArgument(new \PoP\GraphQLParser\Spec\Parser\Ast\Argument('blog', new Literal(trim($this->getComponentProcessorManager()->getComponentProcessor($inputs['blog'])->getValue($inputs['blog'])), \PoP\GraphQLParser\StaticHelpers\LocationHelper::getNonSpecificLocation()), \PoP\GraphQLParser\StaticHelpers\LocationHelper::getNonSpecificLocation()));

        // Allow to add extra inputs
        App::doAction('gd_createupdate_profile:form_data', $mutationField);
    }

    private function getFormInputs()
    {
        $inputs = App::applyFilters(
            'GD_CreateUpdate_Profile:form-inputs',
            array(
                'short_description' => null,
                'display_email' => null,
                'facebook' => null,
                'twitter' => null,
                'linkedin' => null,
                'youtube' => null,
                'instagram' => null,
                // 'blog' => null,
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
}
