<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMutations\SchemaHooks;

use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;
use PoPCMSSchema\PageMutations\TypeResolvers\InputObjectType\CreatePageInputObjectTypeResolverInterface;
use PoPCMSSchema\PageMutations\TypeResolvers\InputObjectType\UpdatePageInputObjectTypeResolverInterface;

trait PageMutationResolverHookSetTrait
{
    protected function isInputObjectTypeResolver(
        InputObjectTypeResolverInterface $inputObjectTypeResolver,
    ): bool {
        return $inputObjectTypeResolver instanceof CreatePageInputObjectTypeResolverInterface
            || $inputObjectTypeResolver instanceof UpdatePageInputObjectTypeResolverInterface;
    }
}
