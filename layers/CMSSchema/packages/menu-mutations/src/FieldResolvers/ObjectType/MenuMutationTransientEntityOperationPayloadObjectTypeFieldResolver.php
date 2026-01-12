<?php

declare(strict_types=1);

namespace PoPCMSSchema\MenuMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\MenuMutations\TypeResolvers\ObjectType\AbstractMenuMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractTransientEntityOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class MenuMutationTransientEntityOperationPayloadObjectTypeFieldResolver extends AbstractTransientEntityOperationPayloadObjectTypeFieldResolver
{
    protected function getObjectIDFieldName(): string
    {
        return 'menuID';
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractMenuMutationPayloadObjectTypeResolver::class,
        ];
    }
}
