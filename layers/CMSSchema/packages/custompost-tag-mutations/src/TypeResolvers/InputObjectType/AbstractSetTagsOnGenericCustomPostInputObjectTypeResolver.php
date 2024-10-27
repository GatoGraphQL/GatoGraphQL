<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostTagMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\Tags\TypeResolvers\ObjectType\TagObjectTypeResolverInterface;
use PoPCMSSchema\CustomPostTagMutations\TypeResolvers\InputObjectType\AbstractSetTagsOnCustomPostInputObjectTypeResolver;
use PoPCMSSchema\Tags\TypeResolvers\ObjectType\GenericTagObjectTypeResolver;

abstract class AbstractSetTagsOnGenericCustomPostInputObjectTypeResolver extends AbstractSetTagsOnCustomPostInputObjectTypeResolver
{
    private ?GenericTagObjectTypeResolver $genericTagObjectTypeResolver = null;

    final protected function getGenericTagObjectTypeResolver(): GenericTagObjectTypeResolver
    {
        if ($this->genericTagObjectTypeResolver === null) {
            /** @var GenericTagObjectTypeResolver */
            $genericTagObjectTypeResolver = $this->instanceManager->getInstance(GenericTagObjectTypeResolver::class);
            $this->genericTagObjectTypeResolver = $genericTagObjectTypeResolver;
        }
        return $this->genericTagObjectTypeResolver;
    }

    protected function getTagTypeResolver(): TagObjectTypeResolverInterface
    {
        return $this->getGenericTagObjectTypeResolver();
    }

    protected function getEntityName(): string
    {
        return $this->__('custom post', 'posttag-mutations');
    }

    protected function addTaxonomyInputField(): bool
    {
        return true;
    }
}
