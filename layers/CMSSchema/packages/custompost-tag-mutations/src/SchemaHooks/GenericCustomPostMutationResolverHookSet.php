<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostTagMutations\SchemaHooks;

use PoPCMSSchema\CustomPostMutations\SchemaHooks\GenericCustomPostMutationResolverHookSetTrait;
use PoPCMSSchema\CustomPostTagMutations\SchemaHooks\AbstractCustomPostMutationResolverHookSet;
use PoPCMSSchema\Tags\TypeResolvers\ObjectType\GenericTagObjectTypeResolver;
use PoPCMSSchema\Tags\TypeResolvers\ObjectType\TagObjectTypeResolverInterface;

class GenericCustomPostMutationResolverHookSet extends AbstractCustomPostMutationResolverHookSet
{
    use GenericCustomPostMutationResolverHookSetTrait;

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
