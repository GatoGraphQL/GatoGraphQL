<?php

declare(strict_types=1);

namespace PoPWPSchema\Media\Overrides\TypeResolvers\EnumType;

use PoPCMSSchema\Media\TypeResolvers\EnumType\MediaItemOrderByEnumTypeResolver as UpstreamMediaItemOrderByEnumTypeResolver;
use PoPWPSchema\CustomPosts\Constants\CustomPostOrderBy;
use PoPWPSchema\CustomPosts\Overrides\TypeResolvers\EnumType\CustomPostOrderByEnumTypeResolverTrait;

/**
 * Use the additional elements added to CustomPost to also add to the MediaItem,
 * since, in WordPress, a media item is a custom post
 */
class MediaItemOrderByEnumTypeResolver extends UpstreamMediaItemOrderByEnumTypeResolver
{
    use CustomPostOrderByEnumTypeResolverTrait;

    /**
     * The "type" needs not be added, since it's always "attachment" for media items
     *
     * @return string[]
     */
    public function getEnumValues(): array
    {
        $additionalMediaItemEnumValues = $this->getAdditionalCustomPostEnumValues();
        array_splice($additionalMediaItemEnumValues, array_search(CustomPostOrderBy::TYPE, $additionalMediaItemEnumValues), 1);
        return array_merge(
            parent::getEnumValues(),
            $additionalMediaItemEnumValues
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
