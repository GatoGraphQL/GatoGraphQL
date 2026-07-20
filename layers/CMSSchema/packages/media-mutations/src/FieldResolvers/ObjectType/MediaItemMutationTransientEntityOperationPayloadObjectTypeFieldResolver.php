<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\MediaMutations\TypeResolvers\ObjectType\MediaUpdateMutationPayloadObjectTypeResolver;
use PoPCMSSchema\MediaMutations\TypeResolvers\ObjectType\RootCreateMediaItemMutationPayloadObjectTypeResolver;
use PoPCMSSchema\MediaMutations\TypeResolvers\ObjectType\RootUpdateMediaItemMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractTransientEntityOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class MediaItemMutationTransientEntityOperationPayloadObjectTypeFieldResolver extends AbstractTransientEntityOperationPayloadObjectTypeFieldResolver
{
    protected function getObjectIDFieldName(): string
    {
        return 'mediaItemID';
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootCreateMediaItemMutationPayloadObjectTypeResolver::class,
            RootUpdateMediaItemMutationPayloadObjectTypeResolver::class,
            MediaUpdateMutationPayloadObjectTypeResolver::class,
        ];
    }
}
