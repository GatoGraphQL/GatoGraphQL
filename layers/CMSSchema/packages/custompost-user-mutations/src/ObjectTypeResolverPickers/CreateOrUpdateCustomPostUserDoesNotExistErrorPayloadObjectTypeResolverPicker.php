<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostUserMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\CustomPostMutations\TypeResolvers\UnionType\AbstractCustomPostMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class CreateOrUpdateCustomPostUserDoesNotExistErrorPayloadObjectTypeResolverPicker extends AbstractUserDoesNotExistErrorPayloadObjectTypeResolverPicker
{
    /**
     * @return array<class-string<UnionTypeResolverInterface>>
     */
    public function getUnionTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractCustomPostMutationErrorPayloadUnionTypeResolver::class,
        ];
    }
}
