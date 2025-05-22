<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostTagMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\CustomPostMutations\ObjectTypeResolverPickers\AbstractCustomPostDoesNotExistErrorPayloadObjectTypeResolverPicker;
use PoPCMSSchema\CustomPostTagMutations\TypeResolvers\UnionType\GenericCustomPostSetTagsMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostTagMutations\TypeResolvers\UnionType\RootSetTagsOnCustomPostMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class CustomPostDoesNotExistErrorPayloadObjectTypeResolverPicker extends AbstractCustomPostDoesNotExistErrorPayloadObjectTypeResolverPicker
{
    /**
     * @return array<class-string<UnionTypeResolverInterface>>
     */
    public function getUnionTypeResolverClassesToAttachTo(): array
    {
        return [
            RootSetTagsOnCustomPostMutationErrorPayloadUnionTypeResolver::class,
            GenericCustomPostSetTagsMutationErrorPayloadUnionTypeResolver::class,
        ];
    }
}
