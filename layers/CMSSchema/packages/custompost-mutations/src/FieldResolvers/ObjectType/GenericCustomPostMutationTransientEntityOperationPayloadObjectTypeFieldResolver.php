<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPostMutations\TypeResolvers\ObjectType\GenericCustomPostUpdateMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CustomPostMutations\TypeResolvers\ObjectType\RootCreateGenericCustomPostMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CustomPostMutations\TypeResolvers\ObjectType\RootUpdateGenericCustomPostMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractTransientEntityOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class GenericCustomPostMutationTransientEntityOperationPayloadObjectTypeFieldResolver extends AbstractTransientEntityOperationPayloadObjectTypeFieldResolver
{
    protected function getObjectIDFieldName(): string
    {
        return 'customPostID';
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootCreateGenericCustomPostMutationPayloadObjectTypeResolver::class,
            RootUpdateGenericCustomPostMutationPayloadObjectTypeResolver::class,
            GenericCustomPostUpdateMutationPayloadObjectTypeResolver::class,
        ];
    }
}
