<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostTagMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\CustomPostTagMutations\TypeResolvers\UnionType\AbstractGenericTagDeleteMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostTagMutations\TypeResolvers\UnionType\AbstractGenericTagUpdateMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostTagMutations\TypeResolvers\UnionType\AbstractRootDeleteGenericTagTermMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostTagMutations\TypeResolvers\UnionType\AbstractRootUpdateGenericTagTermMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\TagMutations\ObjectTypeResolverPickers\AbstractTagTermDoesNotExistErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class TagTermDoesNotExistMutationErrorPayloadObjectTypeResolverPicker extends AbstractTagTermDoesNotExistErrorPayloadObjectTypeResolverPicker
{
    /**
     * @return array<class-string<UnionTypeResolverInterface>>
     */
    public function getUnionTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractRootDeleteGenericTagTermMutationErrorPayloadUnionTypeResolver::class,
            AbstractRootUpdateGenericTagTermMutationErrorPayloadUnionTypeResolver::class,
            AbstractGenericTagDeleteMutationErrorPayloadUnionTypeResolver::class,
            AbstractGenericTagUpdateMutationErrorPayloadUnionTypeResolver::class,
        ];
    }
}
