<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMetaMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\CustomPostMetaMutations\TypeResolvers\UnionType\AbstractCustomPostDeleteMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostMetaMutations\TypeResolvers\UnionType\AbstractRootDeleteCustomPostMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\MetaMutations\ObjectTypeResolverPickers\AbstractTermMetaKeyDoesNotExistErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class TermMetaKeyDoesNotExistErrorPayloadObjectTypeResolverPicker extends AbstractTermMetaKeyDoesNotExistErrorPayloadObjectTypeResolverPicker
{
    /**
     * @return array<class-string<UnionTypeResolverInterface>>
     */
    public function getUnionTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractRootDeleteCustomPostMetaMutationErrorPayloadUnionTypeResolver::class,
            AbstractCustomPostDeleteMetaMutationErrorPayloadUnionTypeResolver::class,
        ];
    }
}
