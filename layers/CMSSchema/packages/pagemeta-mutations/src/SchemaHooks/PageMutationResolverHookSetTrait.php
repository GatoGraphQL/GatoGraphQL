<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMetaMutations\SchemaHooks;

use PoPCMSSchema\PageMutations\TypeResolvers\InputObjectType\CreatePageInputObjectTypeResolverInterface;
use PoPCMSSchema\PageMutations\TypeResolvers\InputObjectType\UpdatePageInputObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;

trait PageMutationResolverHookSetTrait
{
    protected function isInputObjectTypeResolver(
        InputObjectTypeResolverInterface $inputObjectTypeResolver,
    ): bool {
        return $inputObjectTypeResolver instanceof CreatePageInputObjectTypeResolverInterface
            || $inputObjectTypeResolver instanceof UpdatePageInputObjectTypeResolverInterface;
    }
}
