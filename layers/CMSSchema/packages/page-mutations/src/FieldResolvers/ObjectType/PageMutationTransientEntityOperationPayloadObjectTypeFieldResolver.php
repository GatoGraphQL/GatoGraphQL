<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\PageMutations\TypeResolvers\ObjectType\AbstractPageMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractTransientEntityOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class PageMutationTransientEntityOperationPayloadObjectTypeFieldResolver extends AbstractTransientEntityOperationPayloadObjectTypeFieldResolver
{
    protected function getObjectIDFieldName(): string
    {
        return 'pageID';
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractPageMutationPayloadObjectTypeResolver::class,
        ];
    }
}
