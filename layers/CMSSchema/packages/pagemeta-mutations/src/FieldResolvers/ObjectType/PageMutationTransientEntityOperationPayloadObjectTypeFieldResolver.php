<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\PageMetaMutations\TypeResolvers\ObjectType\AbstractPageMetaMutationPayloadObjectTypeResolver;
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
            AbstractPageMetaMutationPayloadObjectTypeResolver::class,
        ];
    }
}
