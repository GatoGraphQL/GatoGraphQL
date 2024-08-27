<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostTagMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\CustomPostMutations\TypeResolvers\UnionType\GenericCustomPostUpdateMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostMutations\TypeResolvers\UnionType\RootCreateGenericCustomPostMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostMutations\TypeResolvers\UnionType\RootUpdateGenericCustomPostMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostTagMutations\TypeResolvers\UnionType\AbstractGenericTagsMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostTagMutations\TypeResolvers\UnionType\GenericTagDeleteMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostTagMutations\TypeResolvers\UnionType\GenericTagUpdateMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostTagMutations\TypeResolvers\UnionType\RootDeleteGenericTagTermMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostTagMutations\TypeResolvers\UnionType\RootUpdateGenericTagTermMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\TagMutations\ObjectTypeResolverPickers\AbstractTagTermDoesNotExistErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class TagTermDoesNotExistErrorPayloadObjectTypeResolverPicker extends AbstractTagTermDoesNotExistErrorPayloadObjectTypeResolverPicker
{
    /**
     * @return array<class-string<UnionTypeResolverInterface>>
     */
    public function getUnionTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractGenericTagsMutationErrorPayloadUnionTypeResolver::class,
            RootDeleteGenericTagTermMutationErrorPayloadUnionTypeResolver::class,
            RootUpdateGenericTagTermMutationErrorPayloadUnionTypeResolver::class,
            GenericTagDeleteMutationErrorPayloadUnionTypeResolver::class,
            GenericTagUpdateMutationErrorPayloadUnionTypeResolver::class,
            GenericCustomPostUpdateMutationErrorPayloadUnionTypeResolver::class,
            RootCreateGenericCustomPostMutationErrorPayloadUnionTypeResolver::class,
            RootUpdateGenericCustomPostMutationErrorPayloadUnionTypeResolver::class,
        ];
    }
}
