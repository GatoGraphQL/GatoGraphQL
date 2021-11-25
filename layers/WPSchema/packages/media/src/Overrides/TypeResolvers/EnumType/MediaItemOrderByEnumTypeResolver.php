<?php

declare(strict_types=1);

namespace PoPWPSchema\Media\Overrides\TypeResolvers\EnumType;

use PoPSchema\Media\TypeResolvers\EnumType\MediaItemOrderByEnumTypeResolver as UpstreamMediaItemOrderByEnumTypeResolver;
use PoPWPSchema\CustomPosts\Overrides\TypeResolvers\EnumType\CustomPostOrderByEnumTypeResolverTrait;

/**
 * Use the additional elements added to CustomPost to also add to the MediaItem,
 * since, in WordPress, a media item is a custom post
 */
class MediaItemOrderByEnumTypeResolver extends UpstreamMediaItemOrderByEnumTypeResolver
{
    use CustomPostOrderByEnumTypeResolverTrait;

    /**
     * @return string[]
     */
    public function getEnumValues(): array
    {
        return array_merge(
            parent::getEnumValues(),
            $this->getAdditionalCustomPostEnumValues()
        );
    }

    public function getEnumValueDescription(string $enumValue): ?string
    {
        if (($enumValueDescription = $this->getAdditionalCustomPostEnumValueDescription($enumValue)) !== null) {
            return $enumValueDescription;
        }
        return parent::getEnumValueDescription($enumValue);
    }
}
