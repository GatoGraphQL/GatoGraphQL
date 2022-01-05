<?php

declare(strict_types=1);

namespace PoPWPSchema\Users\Overrides\TypeResolvers\EnumType;

use PoP\Engine\App;
use PoPSchema\Users\Component;
use PoPSchema\Users\ComponentConfiguration;
use PoPSchema\Users\TypeResolvers\EnumType\UserOrderByEnumTypeResolver as UpstreamUserOrderByEnumTypeResolver;
use PoPWPSchema\Users\Constants\UserOrderBy;

/**
 * The "order by" parameters are defined here:
 *
 * @see https://developer.wordpress.org/reference/classes/wp_user_query/#search-parameters
 */
class UserOrderByEnumTypeResolver extends UpstreamUserOrderByEnumTypeResolver
{
    /**
     * @return string[]
     */
    public function getAdminEnumValues(): array
    {
        $adminEnumValues = parent::getAdminEnumValues();
        /** @var ComponentConfiguration */
        $componentConfiguration = App::getComponentManager()->getComponent(Component::class)->getConfiguration();
        if ($componentConfiguration->treatUserEmailAsAdminData()) {
            $adminEnumValues[] = UserOrderBy::EMAIL;
        }
        return $adminEnumValues;
    }

    /**
     * @return string[]
     */
    public function getEnumValues(): array
    {
        return array_merge(
            parent::getEnumValues(),
            [
                UserOrderBy::INCLUDE,
                UserOrderBy::WEBSITE_URL,
                UserOrderBy::NICENAME,
                UserOrderBy::EMAIL,
            ]
        );
    }

    public function getEnumValueDescription(string $enumValue): ?string
    {
        return match ($enumValue) {
            UserOrderBy::INCLUDE => $this->getTranslationAPI()->__('Order by the included list of user IDs (requires the \'ids\' parameter)', 'users'),
            UserOrderBy::WEBSITE_URL => $this->getTranslationAPI()->__('Order by user\'s website URL', 'users'),
            UserOrderBy::NICENAME => $this->getTranslationAPI()->__('Order by user nicename', 'users'),
            UserOrderBy::EMAIL => $this->getTranslationAPI()->__('Order by user email', 'users'),
            default => parent::getEnumValueDescription($enumValue),
        };
    }
}
