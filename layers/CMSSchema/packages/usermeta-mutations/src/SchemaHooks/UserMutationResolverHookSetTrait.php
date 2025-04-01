<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaMutations\SchemaHooks;

use PoPCMSSchema\UserMutations\TypeResolvers\InputObjectType\CreateUserInputObjectTypeResolverInterface;
use PoPCMSSchema\UserMutations\TypeResolvers\InputObjectType\UpdateUserInputObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;

trait UserMutationResolverHookSetTrait
{
    protected function isInputObjectTypeResolver(
        InputObjectTypeResolverInterface $inputObjectTypeResolver,
    ): bool {
        return $inputObjectTypeResolver instanceof CreateUserInputObjectTypeResolverInterface
            || $inputObjectTypeResolver instanceof UpdateUserInputObjectTypeResolverInterface;
    }
}
