<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaMutations\SchemaHooks;

use PoPCMSSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolverInterface;
use PoPCMSSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;
use PoPCMSSchema\UserMetaMutations\SchemaHooks\AbstractUserMutationResolverHookSet;

class UserMutationResolverHookSet extends AbstractUserMutationResolverHookSet
{
    use UserMutationResolverHookSetTrait;

    private ?UserObjectTypeResolver $userObjectTypeResolver = null;

    final protected function getUserObjectTypeResolver(): UserObjectTypeResolver
    {
        if ($this->userObjectTypeResolver === null) {
            /** @var UserObjectTypeResolver */
            $userObjectTypeResolver = $this->instanceManager->getInstance(UserObjectTypeResolver::class);
            $this->userObjectTypeResolver = $userObjectTypeResolver;
        }
        return $this->userObjectTypeResolver;
    }

    protected function getUserTypeResolver(): UserObjectTypeResolverInterface
    {
        return $this->getUserObjectTypeResolver();
    }
}
