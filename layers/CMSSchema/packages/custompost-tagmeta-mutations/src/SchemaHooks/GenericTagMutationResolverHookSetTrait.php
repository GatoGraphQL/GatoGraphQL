<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostTagMetaMutations\SchemaHooks;

use PoPCMSSchema\CustomPostTagMutations\TypeResolvers\InputObjectType\CreateGenericTagTermInputObjectTypeResolverInterface;
use PoPCMSSchema\CustomPostTagMutations\TypeResolvers\InputObjectType\UpdateGenericTagTermInputObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;

trait GenericTagMutationResolverHookSetTrait
{
    protected function isInputObjectTypeResolver(
        InputObjectTypeResolverInterface $inputObjectTypeResolver,
    ): bool {
        return $inputObjectTypeResolver instanceof CreateGenericTagTermInputObjectTypeResolverInterface
            || $inputObjectTypeResolver instanceof UpdateGenericTagTermInputObjectTypeResolverInterface;
    }
}
