<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\PageMutations\TypeResolvers\ObjectType\PageUpdateMutationPayloadObjectTypeResolver;
use PoPCMSSchema\PageMutations\TypeResolvers\ObjectType\RootCreatePageMutationPayloadObjectTypeResolver;
use PoPCMSSchema\PageMutations\TypeResolvers\ObjectType\RootUpdatePageMutationPayloadObjectTypeResolver;
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
            RootCreatePageMutationPayloadObjectTypeResolver::class,
            RootUpdatePageMutationPayloadObjectTypeResolver::class,
            PageUpdateMutationPayloadObjectTypeResolver::class,
        ];
    }
}
