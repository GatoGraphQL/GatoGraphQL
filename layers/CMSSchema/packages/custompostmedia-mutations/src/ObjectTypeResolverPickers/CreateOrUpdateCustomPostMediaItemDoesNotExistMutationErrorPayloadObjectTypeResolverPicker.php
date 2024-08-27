<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMediaMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\CustomPostMutations\TypeResolvers\UnionType\AbstractCustomPostMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\MediaMutations\ObjectTypeResolverPickers\AbstractMediaItemDoesNotExistErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class CreateOrUpdateCustomPostMediaItemDoesNotExistErrorPayloadObjectTypeResolverPicker extends AbstractMediaItemDoesNotExistErrorPayloadObjectTypeResolverPicker
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
