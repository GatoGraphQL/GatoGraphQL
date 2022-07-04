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

    public function appendMutationDataToFieldDataAccessor(\PoP\ComponentModel\Mutation\FieldDataAccessorInterface $fieldDataProvider): void
    {
        parent::appendMutationDataToFieldDataAccessor($fieldDataProvider);

        $inputs = $this->getFormInputs();
        $fieldDataProvider->add('short_description', trim($this->getComponentProcessorManager()->getComponentProcessor($inputs['short_description'])->getValue($inputs['short_description'])));
        $fieldDataProvider->add('display_email', $this->getComponentProcessorManager()->getComponentProcessor($inputs['display_email'])->getValue($inputs['display_email']));
        $fieldDataProvider->add('facebook', trim($this->getComponentProcessorManager()->getComponentProcessor($inputs['facebook'])->getValue($inputs['facebook'])));
        $fieldDataProvider->add('twitter', trim($this->getComponentProcessorManager()->getComponentProcessor($inputs['twitter'])->getValue($inputs['twitter'])));
        $fieldDataProvider->add('linkedin', trim($this->getComponentProcessorManager()->getComponentProcessor($inputs['linkedin'])->getValue($inputs['linkedin'])));
        $fieldDataProvider->add('youtube', trim($this->getComponentProcessorManager()->getComponentProcessor($inputs['youtube'])->getValue($inputs['youtube'])));
        $fieldDataProvider->add('instagram', trim($this->getComponentProcessorManager()->getComponentProcessor($inputs['instagram'])->getValue($inputs['instagram'])));

        // Allow to add extra inputs
        App::doAction('gd_createupdate_profile:form_data', $fieldDataProvider);
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
