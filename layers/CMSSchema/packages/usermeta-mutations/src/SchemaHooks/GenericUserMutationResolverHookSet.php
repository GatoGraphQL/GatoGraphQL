<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaMutations\SchemaHooks;

use PoPCMSSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolverInterface;
use PoPCMSSchema\Users\TypeResolvers\ObjectType\GenericUserObjectTypeResolver;
use PoPCMSSchema\UserMetaMutations\SchemaHooks\AbstractUserMutationResolverHookSet;

class GenericUserMutationResolverHookSet extends AbstractUserMutationResolverHookSet
{
    use GenericUserMutationResolverHookSetTrait;

    private ?GenericUserObjectTypeResolver $genericUserObjectTypeResolver = null;

    final protected function getGenericUserObjectTypeResolver(): GenericUserObjectTypeResolver
    {
        if ($this->genericUserObjectTypeResolver === null) {
            /** @var GenericUserObjectTypeResolver */
            $genericUserObjectTypeResolver = $this->instanceManager->getInstance(GenericUserObjectTypeResolver::class);
            $this->genericUserObjectTypeResolver = $genericUserObjectTypeResolver;
        }
        return $this->genericUserObjectTypeResolver;
    }

    protected function getUserTypeResolver(): UserObjectTypeResolverInterface
    {
        return $this->getGenericUserObjectTypeResolver();
    }
}
