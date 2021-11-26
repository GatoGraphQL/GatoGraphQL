<?php

declare(strict_types=1);

namespace PoPWPSchema\Users\Overrides\TypeResolvers\EnumType;

use PoPSchema\Users\TypeResolvers\EnumType\UserOrderByEnumTypeResolver as UpstreamUserOrderByEnumTypeResolver;
use PoPWPSchema\Users\Constants\UserOrderBy;

/**
 * The "order by" parameters are defined here:
 *
 * @see https://developer.wordpress.org/reference/classes/wp_query/#order-orderby-parameters
 */
class UserOrderByEnumTypeResolver extends UpstreamUserOrderByEnumTypeResolver
{
    /**
     * @return string[]
     */
    public function getEnumValues(): array
    {
        return array_merge(
            parent::getEnumValues(),
            [
                UserOrderBy::WEBSITE_URL,
            ]
        );
    }

    public function getEnumValueDescription(string $enumValue): ?string
    {
        return match ($enumValue) {
            UserOrderBy::WEBSITE_URL => $this->getTranslationAPI()->__('Order by user\'s website URL', 'users'),
            default => parent::getEnumValueDescription($enumValue),
        };
    }
}
