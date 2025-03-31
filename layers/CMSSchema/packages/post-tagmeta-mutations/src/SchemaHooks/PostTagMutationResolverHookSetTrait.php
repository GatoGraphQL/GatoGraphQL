<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTagMetaMutations\SchemaHooks;

use PoPCMSSchema\PostTagMutations\TypeResolvers\InputObjectType\CreatePostTagTermInputObjectTypeResolverInterface;
use PoPCMSSchema\PostTagMutations\TypeResolvers\InputObjectType\UpdatePostTagTermInputObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;

trait PostTagMutationResolverHookSetTrait
{
    protected function isInputObjectTypeResolver(
        InputObjectTypeResolverInterface $inputObjectTypeResolver,
    ): bool {
        return $inputObjectTypeResolver instanceof CreatePostTagTermInputObjectTypeResolverInterface
            || $inputObjectTypeResolver instanceof UpdatePostTagTermInputObjectTypeResolverInterface;
    }
}
