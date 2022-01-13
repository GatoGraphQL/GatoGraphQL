<?php

declare(strict_types=1);

namespace PoPSchema\Users\ConditionalOnComponent\CustomPosts\SchemaHooks;

use PoP\ComponentModel\TypeResolvers\EnumType\EnumTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\EnumType\HookNames;
use PoP\Root\Hooks\AbstractHookSet;
use PoPSchema\Users\TypeResolvers\EnumType\UserOrderByEnumTypeResolver;
use PoPSchema\Users\ConditionalOnComponent\CustomPosts\Constants\UserOrderBy;

class UserEnumTypeHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        $this->getHooksAPI()->addFilter(
            HookNames::ENUM_VALUES,
            [$this, 'getEnumValues'],
            10,
            2
        );
        $this->getHooksAPI()->addFilter(
            HookNames::ENUM_VALUE_DESCRIPTION,
            [$this, 'getEnumValueDescription'],
            10,
            3
        );
    }

    /**
     * @param string[] $enumValues
     */
    public function getEnumValues(
        array $enumValues,
        EnumTypeResolverInterface $enumTypeResolver,
    ): array {
        if (!($enumTypeResolver instanceof UserOrderByEnumTypeResolver)) {
            return $enumValues;
        }
        return array_merge(
            $enumValues,
            [
                UserOrderBy::CUSTOMPOST_COUNT,
            ]
        );
    }

    public function getEnumValueDescription(
        ?string $enumValueDescription,
        EnumTypeResolverInterface $enumTypeResolver,
        string $enumValue
    ): ?string {
        if (!($enumTypeResolver instanceof UserOrderByEnumTypeResolver)) {
            return $enumValueDescription;
        }
        return match ($enumValue) {
            UserOrderBy::CUSTOMPOST_COUNT => $this->__('Order by custom post count', 'pop-users'),
            default => $enumValueDescription,
        };
    }
}
