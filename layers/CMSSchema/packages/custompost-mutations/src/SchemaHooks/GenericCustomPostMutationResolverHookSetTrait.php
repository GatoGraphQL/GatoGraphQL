<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\SchemaHooks;

use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;
use PoPCMSSchema\CustomPostMutations\TypeResolvers\InputObjectType\CreateGenericCustomPostInputObjectTypeResolverInterface;
use PoPCMSSchema\CustomPostMutations\TypeResolvers\InputObjectType\UpdateGenericCustomPostInputObjectTypeResolverInterface;

trait GenericCustomPostMutationResolverHookSetTrait
{
    protected function isInputObjectTypeResolver(
        InputObjectTypeResolverInterface $inputObjectTypeResolver,
    ): bool {
        return $inputObjectTypeResolver instanceof CreateGenericCustomPostInputObjectTypeResolverInterface
            || $inputObjectTypeResolver instanceof UpdateGenericCustomPostInputObjectTypeResolverInterface;
    }
}
