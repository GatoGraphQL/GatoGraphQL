<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMetaMutations\SchemaHooks;

use PoPCMSSchema\CustomPostMutations\TypeResolvers\InputObjectType\CreateGenericCustomPostInputObjectTypeResolverInterface;
use PoPCMSSchema\CustomPostMutations\TypeResolvers\InputObjectType\UpdateGenericCustomPostInputObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;

trait GenericCustomPostMutationResolverHookSetTrait
{
    protected function isInputObjectTypeResolver(
        InputObjectTypeResolverInterface $inputObjectTypeResolver,
    ): bool {
        return $inputObjectTypeResolver instanceof CreateGenericCustomPostInputObjectTypeResolverInterface
            || $inputObjectTypeResolver instanceof UpdateGenericCustomPostInputObjectTypeResolverInterface;
    }
}
