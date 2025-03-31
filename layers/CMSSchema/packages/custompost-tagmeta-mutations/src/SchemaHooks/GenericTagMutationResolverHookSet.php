<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostTagMetaMutations\SchemaHooks;

use PoPCMSSchema\Tags\TypeResolvers\ObjectType\TagObjectTypeResolverInterface;
use PoPCMSSchema\Tags\TypeResolvers\ObjectType\GenericTagObjectTypeResolver;
use PoPCMSSchema\TagMetaMutations\SchemaHooks\AbstractTagMutationResolverHookSet;

class GenericTagMutationResolverHookSet extends AbstractTagMutationResolverHookSet
{
    use GenericTagMutationResolverHookSetTrait;

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
}
