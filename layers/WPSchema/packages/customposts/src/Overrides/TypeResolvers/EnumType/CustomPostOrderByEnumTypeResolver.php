<?php

declare(strict_types=1);

namespace PoPWPSchema\CustomPosts\Overrides\TypeResolvers\EnumType;

use PoPCMSSchema\CustomPosts\TypeResolvers\EnumType\CustomPostOrderByEnumTypeResolver as UpstreamCustomPostOrderByEnumTypeResolver;

/**
 * The "order by" parameters are defined here:
 *
 * @see https://developer.wordpress.org/reference/classes/wp_query/#order-orderby-parameters
 */
class CustomPostOrderByEnumTypeResolver extends UpstreamCustomPostOrderByEnumTypeResolver
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
