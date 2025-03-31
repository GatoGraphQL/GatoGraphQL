<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMetaMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\CustomPostMetaMutations\TypeResolvers\UnionType\AbstractCustomPostUpdateMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostMetaMutations\TypeResolvers\UnionType\AbstractRootUpdateCustomPostMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\MetaMutations\ObjectTypeResolverPickers\AbstractEntityMetaKeyWithValueDoesNotExistErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class EntityMetaKeyWithValueDoesNotExistErrorPayloadObjectTypeResolverPicker extends AbstractEntityMetaKeyWithValueDoesNotExistErrorPayloadObjectTypeResolverPicker
{
    /**
     * @return array<class-string<UnionTypeResolverInterface>>
     */
    public function getUnionTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractRootUpdateCustomPostMetaMutationErrorPayloadUnionTypeResolver::class,
            AbstractCustomPostUpdateMetaMutationErrorPayloadUnionTypeResolver::class,
        ];
    }
}
