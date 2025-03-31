<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMetaMutations\SchemaHooks;

use PoPCMSSchema\CustomPostMutations\TypeResolvers\InputObjectType\CreateGenericCategoryTermInputObjectTypeResolverInterface;
use PoPCMSSchema\CustomPostMutations\TypeResolvers\InputObjectType\UpdateGenericCategoryTermInputObjectTypeResolverInterface;
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
