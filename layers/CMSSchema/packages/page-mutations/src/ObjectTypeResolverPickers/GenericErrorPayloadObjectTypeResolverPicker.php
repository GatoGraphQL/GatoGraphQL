<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\PageMutations\TypeResolvers\UnionType\AbstractPageMutationErrorPayloadUnionTypeResolver;
use PoPSchema\SchemaCommons\ObjectTypeResolverPickers\AbstractGenericErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class GenericErrorPayloadObjectTypeResolverPicker extends AbstractGenericErrorPayloadObjectTypeResolverPicker
{
    /**
     * @return array<class-string<UnionTypeResolverInterface>>
     */
    public function getUnionTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractPageMutationErrorPayloadUnionTypeResolver::class,
        ];
    }
}
