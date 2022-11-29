<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMediaMutations\Hooks;

use PoPCMSSchema\CustomPostMediaMutations\TypeResolvers\ObjectType\AbstractCustomPostMediaMutationPayloadObjectTypeResolver;
use PoP\ComponentModel\Hooks\AbstractRemoveIDAndSelfFieldsFromObjectTypeHookSet;
use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class RemoveIDAndSelfFieldsFromCustomPostMediaMutationPayloadObjectTypeHookSet extends AbstractRemoveIDAndSelfFieldsFromObjectTypeHookSet
{
    /**
     * @phpstan-return class-string<ObjectTypeResolverInterface|InterfaceTypeResolverInterface>
     */
    protected function getObjectTypeOrInterfaceTypeResolverClass(): string
    {
        return AbstractCustomPostMediaMutationPayloadObjectTypeResolver::class;
    }
}
