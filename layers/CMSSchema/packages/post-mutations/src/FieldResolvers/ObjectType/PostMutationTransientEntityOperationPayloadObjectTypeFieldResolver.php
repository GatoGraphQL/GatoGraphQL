<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\PostMutations\TypeResolvers\ObjectType\AbstractPostMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractTransientEntityOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class PostMutationTransientEntityOperationPayloadObjectTypeFieldResolver extends AbstractTransientEntityOperationPayloadObjectTypeFieldResolver
{
    protected function getObjectIDFieldName(): string
    {
        return 'postID';
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractPostMutationPayloadObjectTypeResolver::class,
        ];
    }
}
