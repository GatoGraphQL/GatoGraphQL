<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaMutations\SchemaHooks;

use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;

trait UserMutationResolverHookSetTrait
{
    protected function isInputObjectTypeResolver(
        InputObjectTypeResolverInterface $inputObjectTypeResolver,
    ): bool {
        // @todo Remove temp code when adding User Mutations
        return false;
        // @todo Re-enable when adding User Mutations
        // return $inputObjectTypeResolver instanceof CreateUserInputObjectTypeResolverInterface
        //     || $inputObjectTypeResolver instanceof UpdateUserInputObjectTypeResolverInterface;
    }
}
