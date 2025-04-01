<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaMutations\SchemaHooks;

use PoPCMSSchema\UserMutations\TypeResolvers\InputObjectType\CreateGenericUserInputObjectTypeResolverInterface;
use PoPCMSSchema\UserMutations\TypeResolvers\InputObjectType\UpdateGenericUserInputObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;

trait GenericUserMutationResolverHookSetTrait
{
    protected function isInputObjectTypeResolver(
        InputObjectTypeResolverInterface $inputObjectTypeResolver,
    ): bool {
        return $inputObjectTypeResolver instanceof CreateGenericUserInputObjectTypeResolverInterface
            || $inputObjectTypeResolver instanceof UpdateGenericUserInputObjectTypeResolverInterface;
    }
}
