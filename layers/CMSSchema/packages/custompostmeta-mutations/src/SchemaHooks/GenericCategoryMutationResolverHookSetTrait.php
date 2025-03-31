<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMetaMutations\SchemaHooks;

use PoPCMSSchema\CustomPostCategoryMutations\TypeResolvers\InputObjectType\CreateGenericCategoryTermInputObjectTypeResolverInterface;
use PoPCMSSchema\CustomPostCategoryMutations\TypeResolvers\InputObjectType\UpdateGenericCategoryTermInputObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;

trait GenericCategoryMutationResolverHookSetTrait
{
    protected function isInputObjectTypeResolver(
        InputObjectTypeResolverInterface $inputObjectTypeResolver,
    ): bool {
        return $inputObjectTypeResolver instanceof CreateGenericCategoryTermInputObjectTypeResolverInterface
            || $inputObjectTypeResolver instanceof UpdateGenericCategoryTermInputObjectTypeResolverInterface;
    }
}
